<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace NameFindProject\src\Counters;

use NameFindProject\src\DB\DbWork;

/**
 * Description of WordCounter
 *
 * @author stager3
 */
class WordCounter
{
    private $start;
    private $finish;
    
    public function __construct($start = null, $finish = null)
    {
        if ($finish===null) {
            $this->finish = DbWork::selectLastId();
        } else {
            $this->finish = $finish;
        }
        if ($start===null) {
            $this->start = DbWork::selectFirstId();
        } else {
            $this->start = $start;
        }
            
    }

    public function getReplacePattern()
    {
        $replacePattern = "";
        $this->setWordsCount();
        return $replacePattern;
    }
    
    private function setWordsCount()
    {
        $tmp = array();
        $rawResult = DbWork::selectRangeArticle($this->start, $this->finish);
        foreach ($rawResult as $rawResultValue) {
            var_dump($rawResultValue['id']);
            //$tmpDecode = \html_entity_decode($rawResultValue['content']);
            //$tmpStripped = \strip_tags($tmpDecode);
            //$tmpReplace = preg_replace("/[\d\p{P}\p{S}]/u", '', $tmpStripped);
            preg_match_all("/\b[а-я]{3,}/u", $rawResultValue['content'], $tmp);
            //var_dump($tmp);
            foreach ($tmp[0] as $word) {
                //var_dump($word);
                $this->calculateWordStatistic($word);
            }
        }
        $this->sendDataFromMemToDb();
        return 0;
    }
      
    private function calculateWordStatistic($word)
    {
        $value = \mb_strtolower($word, 'UTF-8');
        DbWork::insertWordDataMem($value, 1, 1);
    }
    
    private function sendDataFromMemToDb()
    {
        var_dump("Начало сброса БД");
        $data = DbWork::selectAllWordsMem();
        \NameFindProject\src\DB\ConnectDb::mySql()->beginTransaction();
        foreach ($data as $value) {
            DbWork::insertWordData($value['word'], $value['count'], $value['isOnlyUpper']);
        }
        \NameFindProject\src\DB\ConnectDb::mySql()->commit();
        
    }
}
