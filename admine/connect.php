<?php

$server = 'localhost';
$user = 'root';
$pass = 'root';

// $option = array (
//     PDO::MYSQl_ATTR_INIT_COMMAND => 'SET NAMES utf8'

// );

try {

    $con = new PDO("mysql:host=$server;dbname=newShop", $user , $pass );
    $con -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


  
    }



catch(PDOException $e){
    echo 'faild connect' . $e->getMessage(); 
}







