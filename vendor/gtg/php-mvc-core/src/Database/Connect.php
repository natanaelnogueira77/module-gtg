<?php

namespace GTG\MVC\Database;

use PDO;
use PDOException;

class Connect
{
    private static array $instance;
    private static ?PDOException $error = null;

    public static function getInstance(array $database = null): ?PDO
    {
        $dbConf = $database;
        $dbName = "{$dbConf["driver"]}-{$dbConf["dbname"]}@{$dbConf["host"]}";
        $dbDsn = $dbConf["driver"] . ":host=" . $dbConf["host"] . ";dbname=" . $dbConf["dbname"] . ";port=" . $dbConf["port"];

        // DSN alternative for SQL Server (sqlsrv)
        if($dbConf['driver'] == 'sqlsrv') {
            $dbDsn = $dbConf["driver"] . ":Server=" . $dbConf["host"] . "," . $dbConf["port"] . ";Database=" . $dbConf["dbname"];
        }

        if(empty(self::$instance[$dbName])) {
            try {
                self::$instance[$dbName] = new PDO(
                    $dbDsn,
                    $dbConf["username"],
                    $dbConf["passwd"],
                    $dbConf["options"]
                );
            } catch(PDOException $exception) {
                self::$error = $exception;
            }
        }

        return self::$instance[$dbName];
    }

    public static function getError(): ?PDOException
    {
        return self::$error;
    }

    private function __construct()
    {}

    private function __clone()
    {}
}
