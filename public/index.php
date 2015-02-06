<?php
//use NameFindProject\src\Finders\NameFinder;
use NameFindProject\src\DB\ConnectDb;
/*use NameFindProject\src\Counters\WordCounter;
use NameFindProject\src\Simplification\SimplificationName;

use NameFindProject\src\Finders\OrganisationFinder;

use phpMorphy;*/

chdir(dirname(__DIR__));
set_include_path(dirname(__DIR__));
//echo get_include_path();

$time = microtime(true);

require_once 'vendor/autoload.php';



ConnectDb::mySql(require "config/connectDb.php");
$m = new MongoClient('mongodb://root:123456@localhost:27017');
$db = $m->YP;

$queryUsers = "SELECT * FROM yp.user WHERE 1";
$resultUsers = ConnectDb::mySql()->query($queryUsers);
$usersList = array();
$collection = $db->Users;

foreach ($resultUsers as $valueUsers) {
    $userID = $valueUsers['id'];
    $collection->insert($valueUsers);
    $usersList[$userID] = $valueUsers;
}

$collection = $db->Categories;
$queryRubric = "select * from rubric ORDER BY parent_id, `level` DESC;";
$resultRubric = ConnectDb::mySql()->query($queryRubric);
$totalRubricArray = array();
foreach ($resultRubric as $rubricValue) {
    $rubricValue['type'] = "rubric";
    $id = $rubricValue['id'];
    $parent_id = $rubricValue['parent_id'];
    $collection->insert($rubricValue);
    //var_dump($rubricValue);
    
    $totalRubricArray[$id] = $rubricValue;
    //var_dump($totalRubricArray);
    if ($parent_id!=0 && $parent_id!=-1) {
        $result = $collection->update(
            array("id"=>"$id"),
            array(
                '$set'=>array(
                    "parentID"=>$totalRubricArray[$parent_id]['_id']
                )
            )
        );
        var_dump(array("id"=>'"'.$rubricValue['id'].'"'));
        var_dump(array(
                '$set'=>array(
                    "parentID"=>array($totalRubricArray[$parent_id]['_id'])
                )
            ));
        var_dump($result);
    } else {
        $result = $collection->update(
            array("id"=>"$id"),
            array(
                '$set'=>array(
                    "parentID"=>null
                )
            )
        );
    }
}
$collection->update(array(),array('$unset'=>array("id"=>"","0"=>"","parent_id"=>"", "1"=>"", "2"=>"", "3"=>"", "level"=>"",)),array(multiple => true));
var_dump("Рубрики завершены");

$collection = $db->Company;
$queryCompany = "SELECT * FROM yp.object t1 INNER JOIN yp.object_rubric t2 ON t1.id = t2.object_id WHERE t1.head_office_id is null OR t1.head_office_id = '' or t1.is_head_office = 1 or t1.id = t1.head_office_id;";
$resultCompany = ConnectDb::mySql()->query($queryCompany);
$companyList = array();
foreach ($resultCompany as $valueCompany) {
    $rub_parent_id = $valueCompany['rubric_id'];
    $company_id = $valueCompany['id'];
    $valueCompany['type'] = "company";
    $user_id = $valueCompany['user_id'];
    //var_dump($valueBranch['email']);
    $valueCompany['phone_numbers'] = preg_split("/[;]/u", $valueCompany['phone_numbers']);
    $valueCompany['short_phone_numbers'] = preg_split("/[;]/u", $valueCompany['short_phone_numbers']);
    $valueCompany['hr_phone_numbers'] = preg_split("/[;]/u", $valueCompany['hr_phone_numbers']);
    $valueCompany['fax_numbers'] = preg_split("/[;]/u", $valueCompany['fax_numbers']);
    $valueCompany['email'] = preg_split("/[;]/u", $valueCompany['email']);
    $valueCompany['url'] = preg_split("/[;]/u", $valueCompany['url']);
    if (array_key_exists($user_id, $usersList)) {
        $valueCompany['user_id'] = $usersList[$user_id]['_id'];
    }
    
    if (array_key_exists($company_id, $companyList)) {
        $collection->update(
            array("_id"=>$companyList[$company_id]["_id"]),
            array('$addToSet'=>array("parentID"=>$totalRubricArray[$rub_parent_id]['_id']))
        );
    } else {
        $collection->insert($valueCompany);
        $collection->update(
            array("_id"=>$valueCompany["_id"]),
            array('$addToSet'=>array("parentID"=>$totalRubricArray[$rub_parent_id]['_id']))
        );
        $collection->update(
            array("_id"=>$valueCompany["_id"]),
            array('$addToSet'=>array("branchParentID"=>null))
        );
        $companyList[$company_id] = $valueCompany;
    }
}
var_dump("Базовые компании завершены");
$queryBranch = "SELECT * FROM yp.object t1 INNER JOIN yp.object_rubric t2 ON t1.id = t2.object_id where t1.head_office_id is not null AND t1.head_office_id <>'' and t1.is_head_office = 0 and t1.id <> t1.head_office_id ORDER BY t1.head_office_id DESC ;";
$resultBranch = ConnectDb::mySql()->query($queryBranch);
$branchList = array();
foreach ($resultBranch as $valueBranch) {
    $rub_parent_id = $valueBranch['rubric_id'];
    $branch_id = $valueBranch['id'];
    $headOfficeId = $valueBranch['head_office_id'];
    //var_dump($headOfficeId);
    $valueBranch['type'] = "branch";
    $user_id = $valueBranch['user_id'];
    $valueBranch['phone_numbers'] = preg_split("/[;]/u", $valueBranch['phone_numbers']);
    $valueBranch['short_phone_numbers'] = preg_split("/[;]/u", $valueBranch['short_phone_numbers']);
    $valueBranch['hr_phone_numbers'] = preg_split("/[;]/u", $valueBranch['hr_phone_numbers']);
    $valueBranch['fax_numbers'] = preg_split("/[;]/u", $valueBranch['fax_numbers']);
    $valueBranch['email'] = preg_split("/[;]/u", $valueBranch['email']);
    $valueBranch['url'] = preg_split("/[;]/u", $valueBranch['url']);
    if (\array_key_exists($user_id, $usersList)) {
        $valueBranch['user_id'] = $usersList[$user_id]['_id'];
    }
    if (\array_key_exists($branch_id, $branchList)) {
        $collection->update(
            array("_id"=>$branchList[$branch_id]["_id"]),
            array('$addToSet'=>array("parentID"=>$totalRubricArray[$rub_parent_id]['_id']))
        );
    } else {
        //$collection->insert($valueCompany);
        $collection->insert($valueBranch);
        $collection->update(
            array("_id"=>$valueBranch["_id"]),
            array('$addToSet'=>array("parentID"=>$totalRubricArray[$rub_parent_id]['_id']))
        );
        if (array_key_exists($headOfficeId, $companyList)) {
            $collection->update(
                array("_id"=>$valueBranch["_id"]),
                array('$addToSet'=>array("branchParentID"=>$companyList[$headOfficeId]['_id']))
            );
        } elseif (array_key_exists($headOfficeId, $branchList)) {
            $collection->update(
                array("_id"=>$valueBranch["_id"]),
                array('$addToSet'=>array("branchParentID"=>$branchList[$headOfficeId]['_id']))
            );
        } else {
            $collection->update(
                array("_id"=>$valueBranch["_id"]),
                array('$addToSet'=>array("branchParentID"=>null))
            );
        }
        $branchList[$branch_id] = $valueBranch;
    }
}





$time = round(microtime(true) - $time, 3);
echo 'Завершено удачно. Использованно памяти - '
                . round(memory_get_peak_usage() / 1024 / 1024, 3)
                . 'Mb. Затраченное время - ' . $time . 'c';
//var_dump($run2, $_ = null);
