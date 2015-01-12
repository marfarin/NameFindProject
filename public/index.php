<?php
use NameFindProject\src\Finders\NameFinder;
use NameFindProject\src\DB\ConnectDb;
use NameFindProject\src\Counters\WordCounter;

chdir(dirname(__DIR__));
set_include_path(dirname(__DIR__));
//echo get_include_path();
require_once 'vendor/autoload.php';

ConnectDb::mySql(require "config/connectDb.php");
//$run = new NameFinder();
//$run->replaceSingleArticle(176267);
//$run2 = $run->replaceAllArticle(13496, 52203);

$runn = new WordCounter(13496, 51731);
$runn2 = $runn->getReplacePattern();


//var_dump($run2, $_ = null);
