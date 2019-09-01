<?php 
session_start() ; 

$pageTitle = 'login' ; // for title 

if (isset($_SESSION['user'])) {
     header ('Location: index.php'); //  redirect to dashboard 

}
 include 'init.php'; 


 if($_SERVER['REQUEST_METHOD']== 'POST') {

    if(isset($_POST['login'])){

  $username =$_POST['username'] ; 
  $password = $_POST['password'];
  $hashedPass =sha1($password) ; 


        // chick if the user exist in database 

        $stmt = $con->prepare("SELECT  userID , Username , password FROM users WHERE Username = ? AND password = ? "); 
        $stmt->execute(array( $username , $hashedPass)); 
        $get = $stmt->fetch();
        $count = $stmt->rowCount(); // if find the record , count will be 1 , if no 0 ..

        if ($count > 0) {

        $_SESSION['user'] = $username ; // register and save session name 
        $_SESSION['uID'] = $get['userID'] ; // register and save session id



        header ('Location: index.php'); // if u r in database redirect to dashboard 
        exit(); 
        } 

  } else { 

    $form_errors[] = ''; 


        $user = $_POST['username']; 
        $pass1 = $_POST['password'] ; 
        $pass2 = $_POST['password2'] ; 
        $email = $_POST['email'] ; 

if(isset($user)){

$filter_name = filter_var($user , FILTER_SANITIZE_STRING);

if(strlen($filter_name) < 4) {

    $form_errors[] = 'the user name most be more than 4 charcter';
} 
}

if(isset($pass1) && isset($pass2)) {

    if (empty($pass1)) {
        $form_errors[] = 'the password cant be empty' ; 

    }

   

if (sha1($pass1) !== sha1($pass2)) {

    $form_errors[] = 'the password not match' ; 

}
}


if (isset($email)) {

$filter_email = filter_var($email , FILTER_SANITIZE_EMAIL);

if(filter_var($filter_email , FILTER_VALIDATE_EMAIL) != true ) {

    $form_errors[] = 'the email is not validate ';
}

}

  

 //chick if there is no error proced the update operation
    
 if(empty($formErrors))  {
    // check if item is in data base ? 
    
    $check = checkItem("Username" , "users" , $user );
    
    if ($check == 1) {
    
        $form_errors[] = 'sorry this user is used';

        
    } else { 
    
    
            // insert user info in database 
            $stmt = $con->prepare(" INSERT INTO users(Username , password , email ,  Date )
                                                VALUES(:zuser , :zpass , :zemail  , now() ) ");
    
            $stmt->execute(array(
    
                // value from top of $user =  $_POST['username]... etc 
                'zuser' => $user , 
                'zpass' => sha1($pass1) , 
                'zemail'=> $email  
            
    
            ));
            
        
            $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() .'done ' ; 
    
            redirectHome($theMsg ,'back' , 5 ) ; 
        } 
            
            }

        }
 
 }
?>

<div class="container loging-page  ">
    <h1 class="text-center"><span class="selected" data-class="login"> تسجيل الدخول </span> | <span data-class="signup"> مستخدم جديد </span> </h1>

<div class="row justify-content-center ">
    <form class="login"  method="POST" action="<?php echo $_SERVER['PHP_SELF'] ;?>" ><br> 
            <label  > اسم المستخدم  </label>
            <input class="form-control w-90   mb-3" type="text" name="username" autocomplete="off" placeholder="type your username" required="required" />
            <label  > كلمة السر   </label>

            <input class="form-control w-90 mb-3" type="password" name="password" autocomplete="new-password" placeholder="******" required="required"  />
            <input class="btn btn-dark w-50" name="login" type="submit" value="تسجيل الدخول" />
    </form>

    <form class="signup"  method="POST" action="<?php echo $_SERVER['PHP_SELF'] ;?>" >
    <label  > اسم المستخدم  </label>
        <input class="form-control w-90 mb-3 mt-3" pattern=".{4,}" title="username most be larger 4 chars" type="text" name="username" autocomplete="off" placeholder="type your username" required="required" />
        <label  > كلمة السر   </label>

        <input class="form-control w-90 mb-3" minlength="4" type="password" name="password" autocomplete="******" placeholder="password" required />
        <label  > تاكيد كلمة السر  </label>
 
        <input class="form-control w-90 mb-3" minlength="4" type="password" name="password2" autocomplete="******" placeholder="password" required />
        <label  > الايميل   </label>
 
        <input class="form-control w-90 mb-3" type="email" name="email"  placeholder="exampl@examole.com" required="required" />
       
        <input class="btn btn-dark w-50" name="signup" type="submit" value="تسجيل " />
    </form>


    <div class="error">
        <?php 
if(!empty($form_errors)){
   foreach ($form_errors as $error ){
       echo $error . '<br>' ; 
   }

}
?>
</div>
</div>
</div>
</div>

<?php 
include $tpl . 'footer.php'; 
?>