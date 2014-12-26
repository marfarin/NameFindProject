<?php
use NameFindProject\src\Finders\NameFinder;
use NameFindProject\src\DB\DbWork;

chdir(dirname(__DIR__));
set_include_path(dirname(__DIR__));
//echo get_include_path();
require_once 'vendor/autoload.php';

DbWork::instance(require "config/connectDb.php");
$run = new NameFinder();
$run->replaceSingleArticle(176267);