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
    private $words = array();
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


    public function getWords()
    {
        return $this->words;
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
            $tmpDecode = \html_entity_decode($rawResultValue['content']);
            $tmpDecode = \strip_tags($tmpDecode);
            //print_r($tmpDecode);
            $tmpReplace = preg_replace("/[\d]/u", '', $tmpDecode);
            //print_r($tmpReplace);
            
            $tmp = preg_split("/[\s\W]+/u", $tmpReplace);
            print_r($tmp);
            foreach ($tmp as $word) {
                $value = \mb_strtolower($word, 'UTF-8');
                $count = DbWork::selectCountWord($value);
                //var_dump($count);
                if ($count==null) {
                    //$this->words[$value] = 1;
                    DbWork::insertWordData($value, 1);
                } else {
                    //$this->words[$value]++;
                    //var_dump($count[0]['count']);
                    $count[0]['count']++;
                    DbWork::updateWordData($value, (int)$count[0]['count']);
                }
            }
        }
        return 0;
    }
    //put your code here
}
