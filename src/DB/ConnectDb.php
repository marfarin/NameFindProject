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
    private static $instance;
    private static $connectDb;
    
    public function __construct()
    {
    }
    
    public static function mySql(
        $config = [
            'host'=>'localhost',
            'dbname'=>'IccNews',
            'username'=>'root',
            'password'=>'123456',
            'options'=>[\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            ]
        ]
    ) {
        if (self::$instance==null) {
            self::$instance = new self();
            self::init($config);
            return self::$connectDb;
        } else {
            return self::$connectDb;
        }
    }
    
    public static function init($config)
    {
        echo get_include_path();
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
}
