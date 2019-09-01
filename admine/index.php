<?php



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start() ; 

$noNavbar = '' ; // var for hide navbar if not login
$pageTitle = 'login' ; // for title 

if (isset($_SESSION['username'])) {
     header ('Location: dashboard.php'); //  redirect to dashboard 

}
include "init.php";



//chick if user coming from HTTP post request 
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

    $username = $_POST['user']; 
    $password = $_POST['pass']; 
    $hashedPass = sha1($password) ; // for secure

// chick if the user exist in database 

$stmt = $con->prepare("SELECT userID , Username , Password FROM users WHERE Username = ? AND Password = ? AND groupID = 1 LIMIT 1 "); 
$stmt->execute(array($username , $hashedPass)); 

$row = $stmt->fetch(); // array of info from database 

$count = $stmt->rowCount(); // if find the record , count will be 1 , if no 0 ..

//if count >0 this mean the database contain record about this username 
if ($count > 0) {

  // print_r($row);// print info from database 

  $_SESSION['username'] = $username ; // register and save session name 
  $_SESSION['ID'] = $row['userID']; // save id in session 

  header ('Location: dashboard.php'); // if u r in database redirect to dashboard 
  exit(); 
}

}

?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" >
<h4> ADMIN login </h4> 

    <input class="form-control" type="text" name="user" placeholder="username" autocomplete="off" /> 
    <input class="form-control" type="password" name="pass" placeholder="password" autocomplete="new-password" /> 
    <input class="btn btn-primary btn-block" type="submit" value="login" />
</form> 


<?php
include $tpl.'footer.php';
?>