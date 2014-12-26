<?php

namespace NameFindProject\src\Finders;

use NameFindProject\src\DB\DbWork;

class NameFinder
{
    private $namesArray = array();
    
    public function __construct()
    {

    }
    
    public function replace($int)
    {
        return $int;
    }
    
    public function replaceSingleArticle($articleId)
    {
        //DbWork::instance(require "../config/connectDb.php");
        $rawResult = DbWork::selectOneArticle($articleId);
        $rawResult['content'] = html_entity_decode($rawResult['content']);
        //print_r($rawResult);
        $resultWithoutCompany = preg_replace('/(«(.*)»)/U', '', $rawResult['content']);
        print_r($resultWithoutCompany);
        var_dump($resultWithoutCompany);
        $pattern = "/[А-ЯA-Z]+[а-я]+[\s]+[А-ЯA-Z]+[а-я]+[\s]+[А-ЯA-Z]+[а-я]+|[А-ЯA-Z]+[а-я]+[\s]+[А-ЯA-Z]+[а-я]+|[А-ЯA-Z]+[а-я]+|[А-Я]{1}\.[\s]+[А-ЯA-Z]{1}\.[\s]+[А-Я]+[а-я]+|[А-ЯA-Z]{1}\.[\s]+[А-Я]+[а-я]+/u";
        $preg_match_all = preg_match_all($pattern, $resultWithoutCompany, $this->namesArray);
        //echo 123;
        print_r($this->namesArray[0]);
        return 0;
    }
}
