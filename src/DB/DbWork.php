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
    
    public static function insertWordDataMem($keyLFS, $count, $isUpperCase)
    {
        //var_dump("INSERT INTO words (word, count, isOnlyUpper) values ('$keyLFS', '$count', '$isUpperCase')");
        return ConnectDb::mySql()->exec(
            "INSERT INTO wordsMemory (word, count, isOnlyUpper)"
            . " values ('$keyLFS', '$count', '$isUpperCase')"
            . " ON DUPLICATE KEY UPDATE count = count + 1;"
        );
        
    }
     
    public static function selectCountWordMem($word)
    {
        return ConnectDb::mySql()->query(
            "SELECT IFNULL(t1.count,0) as count FROM wordsMemory t1 where t1.word = '$word'",
            \PDO::FETCH_ASSOC
        )->fetchAll();

    }
    
    public static function selectAllWordsMem()
    {
        return ConnectDb::mySql()->query(
            "SELECT * FROM wordsMemory t1 where 1",
            \PDO::FETCH_ASSOC
        )->fetchAll();

    }
    
    public static function insertWordData($keyLFS, $count, $isUpperCase)
    {
        //var_dump("INSERT INTO words (word, count, isOnlyUpper) values ('$keyLFS', '$count', '$isUpperCase')");
        return ConnectDb::mySql()->exec(
            "INSERT INTO words (word, count, onlyUpperCase) values ('$keyLFS', '$count', '$isUpperCase')"
        );
        
    }

    public static function selectlowerWords($word)
    {
        //var_dump("INSERT INTO names (LFS, count, lastLFS) values ('$keyLFS', '$count', '$lastLFS')");
        return ConnectDb::mysql()->query(
            "SELECT * FROM words WHERE word = '$word'",
            \PDO::FETCH_ASSOC
        )->fetchAll();
        
    }
    
    
    public static function selectSityWords($word, $baseWord)
    {
        //var_dump("INSERT INTO names (LFS, count, lastLFS) values ('$keyLFS', '$count', '$lastLFS')");
        $res = "SELECT * FROM "
            . "regionMemNN t1 "
            . "WHERE t1.name = '{$word}' "
            . "OR t1.baseFormName = '{$baseWord}';";
        return ConnectDb::mysql()->query($res, \PDO::FETCH_ASSOC)->fetchAll();
        
    }
    
    public static function insertNameDataMem($keyLFS, $count, $lastLFS)
    {
        //var_dump("INSERT INTO names (LFS, count, lastLFS) values ('$keyLFS', '$count', '$lastLFS')");
        return ConnectDb::mySql()->exec(
            "INSERT INTO namesMemory (LFS, count, lastLFS) values ('$keyLFS', '$count', '$lastLFS') ON DUPLICATE KEY UPDATE count = count + 1;"
        ) or die(print_r(ConnectDb::mySql()->errorInfo(), true));
        
    }
    
    public static function selectAllNamesMem()
    {
        return ConnectDb::mySql()->query(
            "SELECT * FROM namesMemory WHERE 1",
            \PDO::FETCH_ASSOC
        )->fetchAll();

    }
    
    
    public static function selectAllSityMem()
    {
        return ConnectDb::mySql()->query(
            "SELECT * FROM regionMemNN WHERE 1",
            \PDO::FETCH_ASSOC
        )->fetchAll();

    }
    
    public static function insertNameData($keyLFS, $count, $lastLFS)
    {
        //var_dump("INSERT INTO names (LFS, count, lastLFS) values ('$keyLFS', '$count', '$lastLFS')");
        return ConnectDb::mySql()->exec(
            "INSERT INTO names (LFS, count, lastLFS) values ('$keyLFS', '$count', '$lastLFS')"
        );
        
    }
    
    public static function findNamesOnOneWordName($word1)
    {
        return ConnectDb::mySql()->query(
            "SELECT * FROM namesMemory WHERE LFS LIKE '%".$word1."%' AND LFS<>'"
            .$word1."';",
            \PDO::FETCH_ASSOC
        )->fetchAll();
    }
    
    public static function findAllOneWordName($baseName)
    {
        $res = "SELECT * FROM namesMemory WHERE LFS REGEXP '^".$baseName."[А-Я]{0,3}$';";
        var_dump($res);
        return ConnectDb::mySql()->query($res, \PDO::FETCH_ASSOC)->fetchAll();
    }
    
        public static function findOneWordName($baseName)
    {
        $res = "SELECT * FROM namesMemory WHERE LFS REGEXP '^[А-Я]+$';";
        var_dump($res);
        return ConnectDb::mySql()->query($res, \PDO::FETCH_ASSOC)->fetchAll();
    }
    
    public static function findThreeWordName($word1, $word2, $baseName)
    {
        $res = "SELECT * FROM namesMemory WHERE (LFS LIKE '%".$word1."%".$word2.
            "%' OR LFS LIKE '%".$word2."%".$word1.
            "%') AND LFS<>'".$baseName."';";
        var_dump($res);
        return ConnectDb::mySql()->query($res, \PDO::FETCH_ASSOC)->fetchAll();
    }
    
    public static function findAllTwoWordName()
    {
        return ConnectDb::mySql()->query(
            "SELECT * FROM namesMemory WHERE LFS REGEXP '^[А-Я]+( )[А-Я]+$';",
            \PDO::FETCH_ASSOC
        )->fetchAll();
    }
    
    
    public static function isCity()
    {
        return ConnectDb::mySql()->query(
            "(select LFS from names t1 INNER JOIN regionNN t2
ON t1.LFS REGEXP concat('^[А-Я]*',t2.baseFormName,'[А-Я]*$') OR t1.LFS LIKE (t2.name)
WHERE 1 GROUP BY t1.id)
UNION (SELECT word as LFS FROM exeptWords);",
            \PDO::FETCH_ASSOC
        )->fetchAll();
    }
    
    public static function incrementCountReference($word, $count)
    {
        $res = "UPDATE namesMemory SET count = count+'$count' WHERE LFS = '$word';";
        var_dump($res);
        return ConnectDb::mySql()->exec($res);
    }
    
    public static function deleteFalseName($word)
    {
        $res = "DELETE FROM namesMemory WHERE LFS = '$word';";
        var_dump($res);
        return ConnectDb::mySql()->exec($res);
    }
    
    public static function clearNamesMem()
    {
        var_dump("Очистка таблицы namesMemory");
        return ConnectDb::mySql()->exec(
            "TRUNCATE `namesMemory`;"
        );
    }
    
    public static function clearNames()
    {
        var_dump("Очистка таблицы names");
        return ConnectDb::mySql()->exec(
            "TRUNCATE `names`;"
        );
    }
    
    public static function loadNamesMem()
    {
        return ConnectDb::mySql()->exec(
            "INSERT INTO `namesMemory` SELECT * FROM names;"
        );
    }
    
    public static function deleteFalseMemWords()
    {
        $res = "DELETE FROM namesMemory WHERE LFS LIKE '__' OR FS LIKE '_';";
        $res2 = "DELETE FROM namesMemory WHERE LFS LIKE '__ __';";
        $res3 = "DELETE FROM namesMemory WHERE LFS = (SELECT word FROM exeptWords WHERE 1);";
        var_dump($res);
        ConnectDb::mySql()->exec($res2);
        ConnectDb::mySql()->exec($res3);
        return ConnectDb::mySql()->exec($res);
    }
    
    public static function insertDeletedData($word)
    {
        //var_dump("INSERT INTO names (LFS, count, lastLFS) values ('$keyLFS', '$count', '$lastLFS')");
        return ConnectDb::mySql()->exec(
            "INSERT INTO exeptWords (word) values ('$word')"
        );
        
    }
}
