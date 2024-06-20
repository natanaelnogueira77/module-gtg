<?php 

namespace GTG\MVC\Database;

use GTG\MVC\Application;
use GTG\MVC\Database\Drivers\Driver;
use GTG\MVC\Database\Schema\{ Event, Procedure, Table, Trigger };
use PDO, PDOStatement;

final class Database 
{
    private string $projectPath = '';
    private ?Driver $driver = null;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
    }

    public function setDriver(Driver $driver): self
    {
        $this->driver = $driver;
        return $this;
    }

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function exec(string $sql): int 
    {
        $pdo = $this->getConnection();
        return $pdo->exec($sql);
    }

    public function applyMigrations(string $relativePath, string $namespace, ?int $numberOfMigrations = null): void 
    {
        $this->createMigrationsTable();
        $toApplyMigrations = $this->getMigrationsToApply($relativePath);

        $newMigrations = [];
        foreach($toApplyMigrations as $migration) {
            $className = $namespace . '\\' . $migration;
            $instance = new $className($this);
            $this->log("Applying migration {$migration}...");
            $instance->up();
            $this->log("Migration {$migration} applied!");
            $newMigrations[] = $migration;
            if($numberOfMigrations && count($newMigrations) >= $numberOfMigrations) {
                break;
            }
        }

        if(!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        }
        
        $this->log('All migrations were applied!');
    }

    private function getMigrationsToApply(string $relativePath): array 
    {
        return array_diff(
            $this->getFilesFromPath($this->projectPath . '/' . $relativePath), 
            array_map(
                fn($migration) => $migration->migration, 
                $this->getAppliedMigrations()
            )
        );
    }

    public function reverseMigrations(string $relativePath, string $namespace, ?int $number = null): void 
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $toReverseMigrations = array_reverse($appliedMigrations);
        foreach($toReverseMigrations as $migration) {
            $className = $namespace . '\\' . $migration->migration;
            $instance = new $className($this);
            $this->log("Reversing migration {$migration->migration}...");
            $instance->down();
            $this->log("Migration {$migration->migration} reversed!");
            $newMigrationIds[] = $migration->id;
            if($number && count($newMigrationIds) >= $number) break;
        }

        if(!empty($newMigrationIds)) {
            if($number) {
                $this->deleteMigrations($newMigrationIds);
            } else {
                $this->deleteMigrations();
            }
        }

        $this->log('All migrations were reversed!');
    }

    private function getAppliedMigrations(): array 
    {
        $statement = $this->prepare($this->driver->getMigrationsSelectStatement());
        $statement->execute();
        return $statement->fetchAll();
    }

    private function createMigrationsTable(): void
    {
        $this->exec($this->driver->getMigrationsTableCreationStatement());
    }

    public function saveMigrations(array $migrations): void 
    {
        $str = implode(',', array_map(fn($m) => "('{$m}')", $migrations));
        $statement = $this->prepare("INSERT INTO migrations (migration) VALUES {$str}");
        $statement->execute();
    }

    public function deleteMigrations(?array $migrationIds = null): void 
    {
        if($migrationIds) {
            $statement = $this->prepare("DELETE FROM migrations WHERE id IN (" . implode(',', $migrationIds) . ")");
        } else {
            $statement = $this->prepare("DELETE FROM migrations WHERE id >= 1");
        }
        $statement->execute();
    }

    public function applySeeders(string $relativePath, string $namespace): void 
    {
        foreach($this->getSeedersToApply($relativePath) as $seeder) {
            $className = $namespace . '\\' . $seeder;
            $instance = new $className($this);
            $this->log("Applying seeder {$seeder}...");
            $instance->run();
            $this->log("Seeder {$seeder} applied!");
        }

        $this->log('All seeders were applied!');
    }

    private function getSeedersToApply(string $relativePath): array 
    {
        return $this->getFilesFromPath($this->projectPath . '/' . $relativePath);
    }

    private function getFilesFromPath(string $path): array 
    {
        return array_map(
            fn($m) => pathinfo($m, PATHINFO_FILENAME), 
            array_filter(scandir($path), fn($m) => $m !== '.' && $m !== '..')
        );
    }

    public function getConnection(): ?PDO 
    {
        return Connect::getInstance($this->driver->getConnectionData());
    }

    public function prepare(string $sql): PDOStatement|false
    {
        $pdo = $this->getConnection();
        return $pdo->prepare($sql);
    }

    protected function log(string $message): void 
    {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }

    public function createTable(string $tableName, callable $callback): int 
    {
        $table = new Table($tableName);
        $table->create();
        $callback($table);
        return $this->exec($table->build());
    }

    public function alterTable(string $tableName, callable $callback): int 
    {
        $table = new Table($tableName);
        $table->alter();
        $callback($table);
        return $this->exec($table->build());
    }

    public function dropTable(string $tableName): int 
    {
        $table = new Table($tableName);
        $table->drop();
        return $this->exec($table->build());
    }

    public function dropTableIfExists(string $tableName): int 
    {
        $table = new Table($tableName);
        $table->dropIfExists();
        return $this->exec($table->build());
    }

    public function createProcedure(string $procedureName, callable $callback): int 
    {
        $procedure = new Procedure($procedureName);
        $procedure->create();
        $callback($procedure);
        return $this->exec($procedure->build());
    }

    public function dropProcedure(string $procedureName): int 
    {
        $procedure = new Procedure($procedureName);
        $procedure->drop();
        return $this->exec($procedure->build());
    }

    public function dropProcedureIfExists(string $procedureName): int 
    {
        $procedure = new Procedure($procedureName);
        $procedure->dropIfExists();
        return $this->exec($procedure->build());
    }

    public function createEvent(string $eventName, callable $callback): int 
    {
        $event = new Event($eventName);
        $event->create();
        $callback($event);
        return $this->exec($event->build());
    }

    public function dropEvent(string $eventName): int 
    {
        $event = new Event($eventName);
        $event->drop();
        return $this->exec($event->build());
    }

    public function dropEventIfExists(string $eventName): int 
    {
        $event = new Event($eventName);
        $event->dropIfExists();
        return $this->exec($event->build());
    }

    public function createTrigger(string $triggerName, callable $callback): int 
    {
        $trigger = new Trigger($triggerName);
        $trigger->create();
        $callback($trigger);
        return $this->exec($trigger->build());
    }

    public function dropTrigger(string $triggerName): int 
    {
        $trigger = new Trigger($triggerName);
        $trigger->drop();
        return $this->exec($trigger->build());
    }

    public function dropTriggerIfExists(string $triggerName): int 
    {
        $trigger = new Trigger($triggerName);
        $trigger->dropIfExists();
        return $this->exec($trigger->build());
    }
}