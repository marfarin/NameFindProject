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
        $splittedBaseName = preg_split("/[\s]/u", $baseName['LFS']);
        $oneWordsForFirst = DbWork::findAllOneWordName($splittedBaseName[0]);
        foreach ($oneWordsForFirst as $value) {
            DbWork::incrementCountReference($baseName['LFS'], $value['count']);
            DbWork::deleteFalseName($value['LFS']);
        }
        $oneWordsForSecond = DbWork::findAllOneWordName($splittedBaseName[1]);
        foreach ($oneWordsForSecond as $value) {
            DbWork::incrementCountReference($baseName['LFS'], $value['count']);
            DbWork::deleteFalseName($value['LFS']);
        }
    }
    
    public function simplificationTableNames()
    {
        DbWork::clearNamesMem();
        DbWork::loadNamesMem();
        DbWork::deleteFalseMemWords();
        $twoWordsNames = DbWork::findAllTwoWordName();
        //var_dump($twoWordsNames);
        foreach ($twoWordsNames as $twoValue) {
            //var_dump($twoValue['id']);
            $this->simplificThreeWordsName($twoValue);
            $this->simplificOneWordsName($twoValue);
        }
        
        //$oneWordsNames = DbWork::findAllOneWordName();
        //var_dump($oneWordsNames);
        /*foreach ($oneWordsNames as $oneValue) {
            var_dump($oneValue['id']);
            $this->simplificOneWordsName($oneValue);
        }*/
        
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
