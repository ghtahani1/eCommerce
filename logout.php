
<?php 


session_start() ; // important 

session_unset(); 

session_destroy();

header('location:index.php') ; 

exit(); 