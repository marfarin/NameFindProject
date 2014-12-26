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
    
    public function replaceSingleArticle($articleId)
    {
        $resultArray = array();
        //DbWork::instance(require "../config/connectDb.php");
        $rawResult = DbWork::selectOneArticle($articleId);
        $rawResult['content'] = html_entity_decode($rawResult['content']);
        //print_r($rawResult);
        $resultWithoutCompany = preg_replace('/(«(.*)»)/U', '', $rawResult['content']);
        print_r($resultWithoutCompany);
        var_dump($resultWithoutCompany);
        $pattern = "/[А-ЯA-Z]+[а-я]+[\s]+[А-ЯA-Z]+[а-я]+[\s]+[А-ЯA-Z]+[а-я]+|[А-ЯA-Z]+[а-я]+[\s]+[А-ЯA-Z]+[а-я]+|[А-ЯA-Z]+[а-я]+|[А-Я]{1}\.[\s]+[А-ЯA-Z]{1}\.[\s]+[А-Я]+[а-я]+|[А-ЯA-Z]{1}\.[\s]+[А-Я]+[а-я]+/u";
        preg_match_all($pattern, $resultWithoutCompany, $this->namesArray);
        var_dump($this->namesArray);
        foreach ($this->namesArray[0] as $value) {
            if ($this->morphy->isLastPredicted()) {
                var_dump($this->morphy->isLastPredicted());
                array_push($resultArray, $value);
            }
        }

        echo '</br> Это последний массив </br>';
        return $resultArray;
    }
}
