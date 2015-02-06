<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace NameFindProject\src\DB;

/**
 * Description of ConnectDb
 *
 * @author stager3
 */
class ConnectDb
{
    private static $instance = null;
    private static $connectDb = null;
    private static $sqliteMemoryPDO = null;
    
    public function __construct()
    {
    }
    
    public static function mySql(
        $config = [
            'host'=>'localhost',
            'dbname'=>'yp',
            'username'=>'root',
            'password'=>'123456',
            'options'=>[\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            ]
        ]
    ) {
        if (self::$connectDb==null) {
            self::$instance = new self();
            self::init($config);
            return self::$connectDb;
        } else {
            return self::$connectDb;
        }
    }
    
    public static function init($config)
    {
        //echo get_include_path();
        try {
            self::$connectDb = new \PDO(
                'mysql:host='.$config['host'].';dbname='.$config['dbname'].'',
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (Exception $ex) {
            print "Error!: " . $ex->getMessage() . "<br/>";
            //die();
        }
    }
    
    public static function sqliteMemory()
    {
        if (self::$sqliteMemoryPDO === null) {
            self::$sqliteMemoryPDO = new \PDO('sqlite::memory:');
            //self::$sqliteMemoryPDO->query("ATTACH DATABASE '" . __DIR__ . DIRECTORY_SEPARATOR . self::DB_FILE . "' AS hdd");
            //self::$sqliteMemoryPDO->query("CREATE TABLE org_type AS SELECT * FROM hdd.org_type");
            self::$sqliteMemoryPDO->query("CREATE TABLE words (
                'id' INTEGER PRIMARY KEY AUTOINCREMENT,
                'word' TEXT,
                'count' INTEGER NOT NULL DEFAULT (0),
                'isOnlyUpper' INTEGER NOT NULL DEFAULT (1)
            );
            ");
            self::$sqliteMemoryPDO->query("CREATE TABLE names (
                'id' INTEGER PRIMARY KEY AUTOINCREMENT,
                'LFS' TEXT,
                'count' INTEGER NOT NULL DEFAULT (0),
                'lastLFS' INTEGER NOT NULL DEFAULT (1)
            );
            ");
            //self::$sqliteMemoryPDO->query("CREATE TABLE org_similar AS SELECT * FROM hdd.org_similar");
            //self::$sqliteMemoryPDO->query("CREATE TABLE word_list AS SELECT * FROM hdd.word_list");
            //self::$sqliteMemoryPDO->query("DETACH hdd");
        }
        return self::$sqliteMemoryPDO;
    }
}
