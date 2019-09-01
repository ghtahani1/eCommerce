<?php


session_start() ; 
$pageTitle = 'profile' ;


include "init.php";

if(isset($_SESSION['user'])){

$getUser = $con->prepare("SELECT * FROM users WHERE Username =? "); 
$getUser->execute(array($sessionUser)); 

$info = $getUser->fetch();
$userid = $info['userID'];


?>

<div class="my-info">
    <div class="container">
        <div class="card mt-3">
            <div class="card-header bg-info"> my information 
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                   <li><i class="fas fa-lock fa-fw "></i>
                       <span> Name :</span> <?php echo $info['Username']; ?> </li>
                       <li><i class="fas fa-user fa-fw "></i>
                       <span> Full name : </span> <?php echo $info['fullname']; ?> </li>
                       <li><i class="fas fa-envelope fa-fw "></i>
                       <span>Email :</span>  <?php echo $info['email']; ?> </li>
                       <li><i class="fas fa-calendar-alt fa-fw "></i>
                       <span>regester Date  :</span>  <?php echo $info['Date']; ?> </li>
                       <li><i class="fas fa-tags fa-fw "></i>
                       <span> favorite category : </span> </li>
                </ul>

                <a class="btn btn-light"> edite infromation </a>
            </div>
        </div>

     </div>
</div>




<?php

}else {
    header('Location: login.php'); 
    exit();
}

include $tpl.'footer.php';
?>