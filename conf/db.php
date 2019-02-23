<?php

namespace DB;

class settings {
    public static $dsn = "sqlite:clickdb.sqlite3";
    public static $dbName = "ClickLogger";
    public static $tableName = "clicks";
    public static $tablePointsName = "points";
    public static $dbUser = "user";
    public static $dbPass = "password";
    public static $options = array();
}

/**
 * Класс-синглтон для удобной работы с БД
 */
class db {
    private static $PDOInstance = 0;
    
    private function __construct() {
        self::$instance = new PDO(settings::$dsn,settings::$dbUser,settings::$dbPass,settings::$options);
    }
    
    public static function getInstance()
    {
        if (!self::$PDOInstance)
        {
            try {
                self::$PDOInstance = new \PDO(
                        settings::$dsn, 
                        settings::$dbUser, 
                        settings::$dbPass, 
                        settings::$options
                );
                self::$PDOInstance->exec("CREATE TABLE IF NOT EXISTS " . settings::$tableName . 
                    "(id integer primary key autoincrement, ip text, name text, timestamp integer);"
                    );
                self::$PDOInstance->exec("CREATE TABLE IF NOT EXISTS " . settings::$tablePointsName . 
                    "(id integer primary key autoincrement, name text);"
                    );
            } catch (\PDOException $ex) {
                die("PDO Connection Error: " . $ex->getMessage() . "<br/>");
            }
        }
        return self::$PDOInstance;
    }

    public function exec($statement)
    {
        return $this->PDOInstance->exec($statement);
    }
    
    public function query($statement)
    {
        return $this->PDOInstance->query($statement);
    }
    
    public static function createTestDB()
    {   
        self::$PDOInstance->exec("INSERT into " . settings::$tableName . "(ip,name,timestamp) VALUES('127.0.0.1','test1',datetime('now'))");
        self::$PDOInstance->exec("INSERT into " . settings::$tableName . "(ip,name,timestamp) VALUES('192.168.5.1','test2',datetime('now'))");
        self::$PDOInstance->exec("INSERT into " . settings::$tableName . "(ip,name,timestamp) VALUES('192.168.7.2','test3',datetime('now'))");
        
        self::$PDOInstance->exec("INSERT into " . settings::$tablePointsName . "(name) VALUES('test1')");
        self::$PDOInstance->exec("INSERT into " . settings::$tablePointsName . "(name) VALUES('test2')");
        self::$PDOInstance->exec("INSERT into " . settings::$tablePointsName . "(name) VALUES('test3')");
    }
    
}
