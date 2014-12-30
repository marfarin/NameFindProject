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
    
    private function wordExistsOnDictionary($word, &$resultArray)
    {
        $this->morphy->lemmatize(mb_strtoupper($word, 'UTF-8'), phpMorphy::NORMAL);
        if ($this->morphy->isLastPredicted()) {
            //var_dump($this->morphy->isLastPredicted());
            if (\array_key_exists($word, $resultArray)) {
                $resultArray[$word]++;
                echo '</br> Формы слова </br>';
                var_dump($this->morphy->lemmatize(mb_strtoupper($word, 'UTF-8')));
            } else {
                $resultArray[$word] = 1;
                echo '</br> Формы слова </br>';
                var_dump($this->morphy->lemmatize(mb_strtoupper($word, 'UTF-8')));
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
        //print_r($rawResult);
        $resultWithoutCompany = preg_replace('/(«(.*)»)/U', '', $rawResult['content']);
        print_r($resultWithoutCompany);
        //var_dump($resultWithoutCompany);
        $pattern = "/[А-ЯA-Z]+[а-я]+[\s]+[А-ЯA-Z]+[а-я]+[\s]+[А-ЯA-Z]+[а-я]+|[А-ЯA-Z]+[а-я]+[\s]+[А-ЯA-Z]+[а-я]+|[А-ЯA-Z]+[а-я]+|[А-Я]{1}\.[\s]+[А-ЯA-Z]{1}\.[\s]+[А-Я]+[а-я]+|[А-ЯA-Z]{1}\.[\s]+[А-Я]+[а-я]+/u";
        preg_match_all($pattern, $resultWithoutCompany, $this->namesArray);
        
        array_push($this->namesArray[0], 'Ольге Германовне Кирьяновой');
        var_dump($this->namesArray);
        foreach ($this->namesArray[0] as $value) {
            var_dump(mb_strtoupper($value, 'UTF-8'));
            $this->wordExistsOnDictionary($value, $resultArray);
        }
        
        echo '</br> Это последний массив </br>';
        
        var_dump($resultArray);
        return $resultArray;
    }
}
