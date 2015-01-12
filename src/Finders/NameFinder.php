<?php

namespace NameFindProject\src\Finders;

use NameFindProject\src\DB\DbWork;

use phpMorphy;

class NameFinder
{
    private $namesArray = array();
    private $morphy;
    public function __construct()
    {
        $dir = '/home/stager3/workspace/NameFindProject/lib/phpmorphy/dicts';
        $lang = 'ru_RU';
        $opts = array(
            'storage' => 'file',
        );
        try {
            $this->morphy = new phpMorphy($dir, $lang, $opts);
        } catch (phpMorphy_Exception $e) {
            $e->getMessage();
        }
    }
    
    
    private function getBaseFormWord($word)
    {
        $newWordLemm = "";
        $first = true;
        $replasedWords = preg_split('/\s/', $word);
        //var_dump($replasedWords);
        foreach ($replasedWords as $key => $value) {
            $word = $this->morphy->lemmatize(mb_strtoupper($value, 'UTF-8'), phpMorphy::NORMAL);
            if ($word) {
                $replasedWords[$key] = $this->morphy->lemmatize(mb_strtoupper($value, 'UTF-8'), phpMorphy::NORMAL);
            } else {
                $replasedWords[$key] = array($value);
            }
            if ($first === false && $key != end($replasedWords)) {
                $newWordLemm.= " ";
            }
            //$lastKey = end($replasedWords[$key]);
            $newWordLemm .= current($replasedWords[$key]);
            $first = false;
        }
        //var_dump($replasedWords);
        return $newWordLemm;
    }


    private function wordExistsOnDictionary($word, &$resultArray)
    {
        $this->morphy->lemmatize(mb_strtoupper($word, 'UTF-8'), phpMorphy::NORMAL);
        if ($this->morphy->isLastPredicted()) {
            $baseWord = $this->getBaseFormWord($word);
            //var_dump($this->morphy->isLastPredicted());
            if (\array_key_exists($baseWord, $resultArray)) {
                $resultArray[$baseWord]['count']++;
                $resultArray[$baseWord]['name'] = $word;
                DbWork::updateNameData($baseWord, $resultArray[$baseWord]['count'], $word);
            } else {
                $resultArray[$baseWord]['count'] = 1;
                $resultArray[$baseWord]['name'] = $word;
                DbWork::insertNameData($baseWord, 1, $word);
            }
        }
        //var_dump($resultArray);
    }
    
    public function replaceSingleArticle($articleId)
    {
        //DbWork::instance(require "/home/stager3/workspace/NameFindProject/config/connectDb.php");
        $resultArray = array();
        //DbWork::instance(require "../config/connectDb.php");
        $rawResult = DbWork::selectOneArticle($articleId);
        $rawResult['content'] = \html_entity_decode($rawResult['content']);
        print_r($rawResult);
        $resultWithoutCompany = preg_replace('/(«(.*)»)/U', '', $rawResult['content']);
        //print_r($resultWithoutCompany);
        //var_dump($resultWithoutCompany);
        $pattern = "/[А-ЯA-Z]+[а-я]+[\s]+[А-ЯA-Z]+[а-я]+[\s]+[А-ЯA-Z]+[а-я]+|[А-ЯA-Z]+[а-я]+[\s]+[А-ЯA-Z]+[а-я]+|[А-ЯA-Z]+[а-я]+|[А-Я]{1}\.[\s]+[А-ЯA-Z]{1}\.[\s]+[А-Я]+[а-я]+|[А-ЯA-Z]{1}\.[\s]+[А-Я]+[а-я]+/u";
        preg_match_all($pattern, $resultWithoutCompany, $this->namesArray);
        
        //array_push($this->namesArray[0], 'Ольге Германовне Кирьяновой');
        //var_dump($this->namesArray);
        foreach ($this->namesArray[0] as $value) {
            //var_dump(mb_strtoupper($value, 'UTF-8'));
            $this->wordExistsOnDictionary($value, $resultArray);
        }
        
        echo '</br> Это последний массив </br>';
        
        //var_dump($resultArray);
        return $resultArray;
    }
    
    public function replaceAllArticle($articleId1, $articleId2)
    {
        //DbWork::instance(require "/home/stager3/workspace/NameFindProject/config/connectDb.php");
        $resultArray = array();
        //DbWork::instance(require "../config/connectDb.php");
        $rawResult = DbWork::selectRangeArticle($articleId1, $articleId2);
        //var_dump($rawResult);
        foreach ($rawResult as $rawResultValue) {
            //$i++;
            $rawResultValue['content'] = \html_entity_decode($rawResultValue['content']);
            //print_r($rawResultValue['content']);
            $resultWithoutCompany = preg_replace('/(«(.*)»)/U', '', $rawResultValue['content']);
            //print_r($resultWithoutCompany);
            //var_dump($resultWithoutCompany);
            $pattern = "/[А-ЯA-Z]{1}[а-я]+[\s]+[А-ЯA-Z]{1}[а-я]+[\s]+[А-ЯA-Z]{1}[а-я]+|[А-ЯA-Z]{1}[а-я]+[\s]+[А-ЯA-Z]{1}[а-я]+|[А-ЯA-Z]{1}[а-я]+|[А-Я]{1}\.[\s]+[А-ЯA-Z]{1}\.[\s]+[А-Я]{1}[а-я]+|[А-ЯA-Z]{1}\.[\s]+[А-Я]{1}[а-я]+/u";
            preg_match_all($pattern, $resultWithoutCompany, $this->namesArray);

            //var_dump($resultArray);
            foreach ($this->namesArray[0] as $value) {
                //var_dump(mb_strtoupper($value, 'UTF-8'));
                $this->wordExistsOnDictionary($value, $resultArray);
            }
        }
        //echo $i;
        echo '</br> Это последний массив  23</br>';
        
        //var_dump($resultArray);
        return $resultArray;
    }
}
