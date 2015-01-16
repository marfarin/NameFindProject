<?php

namespace NameFindProject\src\Finders;

use NameFindProject\src\DB\DbWork;
use NameFindProject\src\DB\ConnectDb;

class OrganisationFinder
{
    public function replaceAllOrganisation($articleId1, $articleId2)
    {
        $resultArray = array();
        $rawResult = DbWork::selectRangeArticle($articleId1, $articleId2);
        foreach ($rawResult as $rawResultValue) {
            $resultCompany = array();
            $rawResultValue['content'] = \html_entity_decode($rawResultValue['content']);
            preg_match_all(
                '/(([А-Яа-я]{3,})[\s][«]{1}(.{1,80})[»]{1})/Uu',
                $rawResultValue['content'],
                $resultCompany
            );
            var_dump($resultCompany);
            //$pattern = "/[А-ЯA-Z]{1}[а-я]+[\s]+[А-ЯA-Z]{1}[а-я]+[\s]+[А-ЯA-Z]{1}[а-я]+|[А-ЯA-Z]{1}[а-я]+[\s]+[А-ЯA-Z]{1}[а-я]+|[А-ЯA-Z]{1}[а-я]+|[А-Я]{1}\.[\s]+[А-ЯA-Z]{1}\.[\s]+[А-Я]{1}[а-я]+|[А-ЯA-Z]{1}\.[\s]+[А-Я]{1}[а-я]+/u";
            //preg_match_all($pattern, $resultWithoutCompany, $this->namesArray);
            //var_dump("сиськи");
            //var_dump($resultArray);
            //foreach ($this->namesArray[0] as $value) {
                //var_dump(mb_strtoupper($value, 'UTF-8'));
                //$filterValue = $this->testValue($value);
                //if ($filterValue!="") {
                //    $this->wordExistsOnDictionary($filterValue, $resultArray);
                //}
            //}
        }
        //echo $i;
        //echo '</br> Это последний массив  23</br>';
        
        //var_dump($resultArray);
        //return $resultArray;
        //$this->sendNamesFromMemToDb();
        
    }
}
