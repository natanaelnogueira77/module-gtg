<?php

namespace GTG\DataLayer;

use PDO;
use PDOException;

/**
 * Class Connect
 * @package GTG\DataLayer
 */
class Connect 
{
    public static $config;
    /** @var PDO */
    private static $instance;

    /** @var PDOException */
    private static $error;

    /**
     * @return PDO
     */

    public static function getInstance(): ?PDO 
    {
        if(empty(self::$instance)) {
            try {
                $pdoConfig = self::getConfig(DATA_LAYER);
                
                self::$instance = new PDO($pdoConfig, DATA_LAYER['username'], DATA_LAYER['passwd'], DATA_LAYER['options']);
            } catch (PDOException $exception) {
                self::$error = $exception;
            }
        }
        return self::$instance;
    }

    private static function getConfig(array $config): string 
    {
        $pdoConfig = '';

        if($config['driver'] == 'mysql') {
            $pdoConfig = $config['driver'] 
                . ':host=' . $config['host'] 
                . ';dbname=' . $config['dbname'] 
                . ';port=' . $config['port'];
        } elseif($config['driver'] == 'sqlsrv') {
            $pdoConfig = $config['driver'] 
                . ':Server=' . $config['host'] 
                . ';Database=' . $config['dbname'] 
                . ';';
        }

        return $pdoConfig;
    }

    /**
     * @return PDOException|null
     */
    public static function getError(): ?PDOException 
    {
        return self::$error;
    }

    /**
     * Connect constructor.
     */
    private function __construct() 
    {
    
    }

    /**
     * Connect clone.
     */
    private function __clone() 
    {

    }
}
