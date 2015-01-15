<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace NameFindProject\src\Simplification;

use NameFindProject\src\DB\DbWork;

use NameFindProject\src\DB\ConnectDb;

/**
 * Description of SimplificationName
 *
 * @author stager3
 */
class SimplificationName
{
    private function simplificThreeWordsName($baseName)
    {
        $baseNameSplit = preg_split('/[\s]/u', $baseName['LFS']);
        //var_dump($baseNameSplit);
        $allThreeWordNames = DbWork::findThreeWordName($baseNameSplit[0], $baseNameSplit[1], $baseName['LFS']);
        //var_dump($allThreeWordNames);
        foreach ($allThreeWordNames as $value) {
            var_dump($value['id']);
            DbWork::incrementCountReference($baseName['LFS'], $value['count']);
            DbWork::deleteFalseName($value['LFS']);
        }
    }
    
    private function simplificOneWordsName($baseName)
    {
        $allTwoWordNames = DbWork::findNamesOnOneWordName($baseName['LFS']);
        foreach ($allTwoWordNames as $value) {
            DbWork::incrementCountReference($value['LFS'], $baseName['count']);
            DbWork::deleteFalseName($baseName['LFS']);
        }
    }
    
    public function simplificationTableNames()
    {
        DbWork::clearNamesMem();
        DbWork::loadNamesMem();
        $twoWordsNames = DbWork::findAllTwoWordName();
        //var_dump($twoWordsNames);
        foreach ($twoWordsNames as $twoValue) {
            //var_dump($twoValue['id']);
            $this->simplificThreeWordsName($twoValue);
        }
        
        $oneWordsNames = DbWork::findAllOneWordName();
        //var_dump($oneWordsNames);
        foreach ($oneWordsNames as $oneValue) {
            var_dump($oneValue['id']);
            $this->simplificOneWordsName($oneValue);
        }
        
        DbWork::clearNames();
        $this->sendNamesFromMemToDb();
    }
    
    
    private function sendNamesFromMemToDb()
    {
        var_dump("Начало сброса БД");
        $data = DbWork::selectAllNamesMem();
        \NameFindProject\src\DB\ConnectDb::mySql()->beginTransaction();

        foreach ($data as $value) {
            DbWork::insertNameData($value['LFS'], $value['count'], $value['lastLFS']);
        }
        \NameFindProject\src\DB\ConnectDb::mySql()->commit();

        
    }
}
