<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace NameFindProject\src\DB;

/**
 * Description of DB
 *
 * @author stager3
 */
class DbWork
{
    private static $instance;
    private static $connectDb;
    public function __construct()
    {
    }
    
    public static function instance($config)
    {
        if (self::$instance==null) {
            self::$instance = new self();
            self::init($config);
        } else {
            return self::$instance;
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
    
    public static function selectOneArticle($articleId)
    {
        return self::$connectDb->query(
            "SELECT * FROM `news` t1 WHERE t1.id = '$articleId' LIMIT 1",
            \PDO::FETCH_ASSOC
        )->fetchAll()[0];
    }
    
    public static function selectAllArticle()
    {
        return self::$connectDb->query(
            "SELECT * FROM `news`",
            \PDO::FETCH_ASSOC
        )->fetchAll();
    }
    
    public static function selectRangeArticle($minId, $maxId)
    {
        return self::$connectDb->query(
            "SELECT * FROM `news` t1 WHERE t1.id > '$minId' AND t1.id < '$maxId' LIMIT 1",
            \PDO::FETCH_ASSOC
        )->fetchAll();
    }
}
