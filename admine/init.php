<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connect.php'; 

//Routes 

$tpl = 'include/templet/'; // templet directory
$lang = 'include/language/' ; 
$func = 'include/function/' ; 
$css = 'layout/css/'; // css directory
$js = 'layout/js/'; // js dir

//include the important file 

include $func.'function.php'; 
include $lang.'english.php';
include $tpl.'header.php';


// include navbar on all pages expect the one with $noNavbar variable 

if(!isset($noNavbar)) { include $tpl.'navbar.php'; } 




