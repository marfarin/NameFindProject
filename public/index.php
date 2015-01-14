<?php
use NameFindProject\src\Finders\NameFinder;
use NameFindProject\src\DB\ConnectDb;
use NameFindProject\src\Counters\WordCounter;

chdir(dirname(__DIR__));
set_include_path(dirname(__DIR__));
//echo get_include_path();

$time = microtime(true);

require_once 'vendor/autoload.php';



ConnectDb::mySql(require "config/connectDb.php");
$run = new NameFinder();
//$run->replaceSingleArticle(176267);
$run2 = $run->replaceAllArticle(13495, 327071);

//$runn = new WordCounter(13496, 51200);
//$runn2 = $runn->getReplacePattern();

  $string="привет";
  $char = mb_strtoupper(mb_substr($string, 0, 1, "utf-8"), "utf-8"); // это первый символ
  $string[0]=$char[0];
  //$string[1]=$char[1];


$time = round(microtime(true) - $time, 3);
echo 'Завершено удачно. Использованно памяти - '
                . round(memory_get_peak_usage() / 1024 / 1024, 3)
                . 'Mb. Затраченное время - ' . $time . 'c';
//var_dump($run2, $_ = null);
