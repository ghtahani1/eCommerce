<?php 


ob_start() ; // output buffering start 

session_start() ; 

$pageTitle = 'dashboard' ; // for title 

if (isset($_SESSION['username'])) {

    include 'init.php'; 

 
// $do =isset($_GET['do'])? $_GET['do'] : 'manage' ;  // اختصار دالة if


$do = '';

if (isset ($_GET['do'])) { // ex: www.page.php?do=tat

$do = $_GET['do']; 

} else {

    $do = 'manage'; // main page
}



// if the page is main page with [$do ] action

if ($do == 'manage') {

    echo 'welcome' ; 
    echo '<a href="?do=add"> add new category </a>';

}  elseif ( $do == 'add') {

    echo ' you are in add page ' ; 
}
elseif ( $do == 'insert') {

    echo ' you are in add page ' ; 
}elseif ( $do == 'add') {

    echo ' you are in add page ' ; 
} else { 
    echo ' error no page ' ; 
}

}