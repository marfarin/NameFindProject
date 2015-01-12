<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace NameFindProject\src\DB;

use NameFindProject\src\DB\ConnectDb;

/**
 * Description of DB
 *
 * @author stager3
 */


class DbWork
{
    /**
     * @param type $articleId
     * @return type
     */
    public static function selectOneArticle($articleId)
    {
        return ConnectDb::mySql()->query(
            "SELECT * FROM `news` t1 WHERE t1.id = '$articleId' LIMIT 1",
            \PDO::FETCH_ASSOC
        )->fetchAll()[0];
    }
    /**
     * @return type
     */
    public static function selectAllArticle()
    {
        return ConnectDb::mySql()->query(
            "SELECT * FROM `news`",
            \PDO::FETCH_ASSOC
        )->fetchAll();
    }
    /**
     * @param type $minId
     * @param type $maxId
     * @return type
     */
    public static function selectRangeArticle($minId, $maxId)
    {
        return ConnectDb::mySql()->query(
            "SELECT * FROM `news` t1 WHERE t1.id > '$minId' AND t1.id < '$maxId'",
            \PDO::FETCH_ASSOC
        )->fetchAll();
    }
    public static function selectLastId()
    {
        return ConnectDb::mySql()->query(
            "SELECT t1.id FROM `news` t1 ORDER BY t1.id desc limit 1",
            \PDO::FETCH_ASSOC
        )->fetchAll()[0]['id'];
    }
    
    public static function selectFirstId()
    {
        return ConnectDb::mySql()->query(
            "SELECT t1.id FROM `news` t1 ORDER BY t1.id limit 1",
            \PDO::FETCH_ASSOC
        )->fetchAll()[0]['id'];
    }
    
    public static function insertNameData($keyLFS, $count, $lastLFS)
    {
        var_dump("INSERT INTO names (LFS, count, lastLFS) values ('$keyLFS', '$count', '$lastLFS')");
        return ConnectDb::mySql()->exec(
            "INSERT INTO names (LFS, count, lastLFS) values ('$keyLFS', '$count', '$lastLFS')"
        ) or die(print_r(ConnectDb::mySql()->errorInfo(), true));
        
    }
    
    public static function updateNameData($keyLFS, $count, $lastLFS)
    {
        var_dump("UPDATE names SET count = '$count', lastLFS = '$lastLFS' WHERE LFS = '$keyLFS'");
        return ConnectDb::mySql()->exec(
            "UPDATE names SET count = '$count', lastLFS = '$lastLFS' WHERE LFS = '$keyLFS'"
        ) or die(print_r(ConnectDb::mySql()->errorInfo(), true));
    }
    
    public static function insertWordData($keyLFS, $count)
    {
        var_dump("INSERT INTO words (word, count) values ('$keyLFS', '$count')");
        return ConnectDb::mySql()->exec(
            "INSERT INTO words (word, count) values ('$keyLFS', '$count')"
        ) or die(print_r(ConnectDb::mySql()->errorInfo(), true));
        
    }
    
    public static function updateWordData($keyLFS, $count)
    {
        var_dump("UPDATE words SET count = '$count' WHERE word = '$keyLFS'");
        return ConnectDb::mySql()->exec(
            "UPDATE words SET count = '$count' WHERE word = '$keyLFS'"
        ) or die(print_r(ConnectDb::mySql()->errorInfo(), true));
    }
    
    public static function selectCountWord($word)
    {
        return ConnectDb::mySql()->query(
            "SELECT IFNULL(t1.count,0) as count FROM words t1 where t1.word = '$word'",
            \PDO::FETCH_ASSOC
        )->fetchAll();

    }
}
