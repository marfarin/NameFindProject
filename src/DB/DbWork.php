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
}
