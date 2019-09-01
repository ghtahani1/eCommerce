<?php
/*
=== manage memper page 
== you can add | edite || delet member from here 

*/

session_start() ; 

if (isset($_SESSION['username'])) {

    $pageTitle = 'MEMBERS' ; // for title 

    include 'init.php'; 

     $do =isset($_GET['do'])? $_GET['do'] : 'manage' ;  // اختصار دالة if

    

     if ($do == 'manage') { //manage member page
     
  

       // SElect all users except admin 
     $stmt = $con->prepare("SELECT * FROM users  WHERE groupID != 1   ORDER BY userID DESC  ");
     $stmt->execute(); 

     // assign to variable 

     $rows = $stmt->fetchAll(); 
     
     if(!empty($rows)) { 
     ?> 
        <h1 class="text-center"> ادآرة العملاء  </h1>

        <div class=" container">
        <div class="table-responsive">
                <table class="main-table manage-members text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td> الاسم </td>
                        <td> الاسم الكامل </td> 
                        <td> البريد  </td>
                        <td> تاريخ التسجيل </td>
                        <td> التحكم </td> 
                    </tr>

                <?php

                foreach($rows as $row) {
                    echo "<tr>" ; 
                     echo "<td>" . $row['userID'] . "</td>" ; 
                     echo "<td>" . $row['Username']. "</td>" ;
                     echo "<td>" . $row['fullname']. "</td>" ; 
                     echo "<td>" . $row['email']. "</td>" ; 

                     echo "<td>".$row['Date'] ."</td>" ; 
                     echo "<td> 
 
                     <a href='member.php?do=edit&userid= "  . $row['userID'] . " '  class='ed-btn'> تعديل </a>
                     <a href='member.php?do=delete&userid= "  . $row['userID'] . " 'class='del-btn confirm '> حذف </a> "; 
                    

                     //if regstatus = 0 show the button .. we can use it any where 
                 


                      echo "</td>"; 

                    echo "</tr>"; 
                }

                ?>

         <!-- for confirm the delet btn  -->
        <script>   $('.confirm').click(function(){ return confirm('هل انت متأكد  vv ?'); }); </script> 
            
               </table>
     </div>

<?php } else { echo " <div class='container' style='margin-top:200px;'>  لايوجد عملاء ..  </div>   " ; } ?>


     <a href="member.php?do=add" class="cart-btn"><i class="fas fa-plus"></i> اضافة عميل جديد</a>
     </div>


     <?php } elseif  ($do == 'add') { ?>



        <h1 class="text-center">  اضافة عميل  </h1>
        <div class=" container">
        <form class="form-horizontal" action="?do=insert" method="POST" enctype="multipart/form-data">
        <!-- username field  -->
        <div class="form=group">
        <label class="col-sm-2 control-label"> الاسم المستعار </label>
            <div class="col-sm-10 col-md-5">
                <input type="text" name="username"  class="form-control" autocomplete="off" required="required" />
        </div>
        </div>
            <!-- fullname field  -->
            <div class="form=group">
        <label class="col-sm-2 control-label">الاسم الكامل </label>
            <div class="col-sm-10 col-md-5">
                <input type="text" name="fullname"  class="form-control" required="required" />
        </div>
        </div>
       
        <!-- password field  -->
        <div class="form=group">
        <label class="col-sm-2 control-label"> الرقم السري </label>
            <div class="col-sm-10 col-md-5 ">
                <input type="password" name="password" class="password form-control" autocomplete="new-password" required="required" >
               <i class="show-pass fas fa-eye fa-2x"></i>
            </div>
        </div>
        <!-- email field  -->
        <div class="form=group">
        <label class="col-sm-2 control-label">البريد الالكتروني </label>
            <div class="col-sm-10 col-md-5">
                <input type="email" name="email"   class="form-control" required="required"  />
        </div>
        </div>
    
    <br>
        <!-- submit  -->
        <div class="form=group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="اضافة عميل" class="cart-btn" />
        </div>
        </div>




        </form>
        </div> 

<?php 
     } elseif($do == 'insert') { 

        //insert member page 
    
    
    if ($_SERVER['REQUEST_METHOD'] == "POST"){

        echo "<h1 class='text-center'>  اضافة عميل </h1>" ; 
        echo "<div class='container'>";






        // get variable from the form 
    
        $pass = $_POST['password']; 
        $user = $_POST['username']; 
        $email = $_POST['email']; 
        $full = $_POST['fullname']; 
    
        // trick for password 
    
        $hashpass =sha1( $_POST['password']);
    
    //form validate 
    
    $formErrors= array(); 
    
    // if (strlen($user < 3 )){
    //     $formErrors[] = ' user cant be less than 4' ; 
    // }
    
    if (strlen($user > 20 )){
        $formErrors[] = 'user cant be more than 20' ; 
    }
    
    if(empty($user)){
        $formErrors[] = 'user cant be empty' ; 
    } 
    if(empty($pass)){
        $formErrors[] = 'password cant be empty' ; 
    } 
    if(empty($full)){
        $formErrors[] = 'fullname cant be empty  ' ; 
    } if(empty($email)){
        $formErrors[] = 'email cant be empty' ; 
    } 
   
  
  
    
    foreach ($formErrors as $error) {
        echo '<div class="alert alert-danger">' .  $error . '</div>' ; 
    }
    
    //chick if there is no error proced the update operation
    
    if(empty($formErrors))  {
        


// check if item is in data base ? 

$check = checkItem("Username" , "users" , $user );


if ($check == 1) {
    $theMsg =  '<div class="alert alert-danger"> sorry this user is exist </div>' ; 

    redirectHome($theMsg , 'back') ; 

} else { 


        // insert user info in database 
        $stmt = $con->prepare(" INSERT INTO users(username , password , email , fullname ,  Date  )
                                            VALUES(:zuser , :zpass , :zemail , :zname  , now() ) ");

        $stmt->execute(array(

            // value from top of $user =  $_POST['username]... etc 
            'zuser' => $user , 
            'zpass' => $hashpass , 
            'zemail'=> $email , 
            'zname' => $full ,

        

        ));
        
    
        $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() .'تمت اضافة العميل  ' ; 

        redirectHome($theMsg , 'back' , 9 ) ; 
    }
      
    

    } 
    
    
    } else {
    
        echo "<div class='container'>" ; 
      $theMsg =  "<div class='alert alert-danger'> sorry you cant browser this page </div> "; 

        redirectHome($theMsg , 'back' , 9 ) ; 

        echo "</div>" ; 

    }
   
    
    echo "</div>" ; 

     

    }  elseif ( $do == 'edit') { // EDIT page  
        
        // for secure for the user id 

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? 
        intval($_GET['userid']) : 0 ; 
        // select all data depend on this ID 

        $stmt = $con->prepare("SELECT * FROM users WHERE UserID= ? LIMIT 1 "); 
        $stmt->execute(array($userid)); 
        $row = $stmt->fetch(); // array of info from database for this user 
        
        $count = $stmt->rowCount(); // variable 


        if ($count > 0 ){   ?> 

        <!-- // if you have the same userID ,, show the form  -->

            <h1 class="text-center">  تعديل البيانات</h1>
            <div class=" container">
            <form class="form-horizontal" action="?do=update" method="POST">
                <input type="hidden" name="userid"  value="<?php echo $userid ?>" /> 
            <!-- username field  -->
            <div class="form=group">
            <label class="col-sm-2 control-label"> الاسم المستعار </label>
                <div class="col-sm-10 col-md-5">
                    <input type="text" name="username"  value ="<?php echo $row['Username'] ; ?>" class="form-control" autocomplete="off" required="required" />
            </div>
            </div>
            <!-- password field  -->
            <div class="form=group">
            <label class="col-sm-2 control-label"> الرقم السري </label>
                <div class="col-sm-10 col-md-5 ">
                <input type="hidden" name="oldpassword" class="form-control"  value ="<?php echo $row['password'] ; ?>" />
                    <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="leave blank if you dont wand to change" >
            </div>
            </div>
            <!-- email field  -->
            <div class="form=group">
            <label class="col-sm-2 control-label"> البريد الالكتروني </label>
                <div class="col-sm-10 col-md-5">
                    <input type="email" name="email"  value ="<?php echo $row['email'] ; ?>" class="form-control" required="required" />
            </div>
            </div>
            <!-- fullname field  -->
            <div class="form=group">
            <label class="col-sm-2 control-label">الاسم الكامل </label>
                <div class="col-sm-10 col-md-5">
                    <input type="text" name="fullname"  value ="<?php echo $row['fullname'] ; ?>" class="form-control" required="required" />
            </div>
            </div>
        <br>
            <!-- submit  -->
            <div class="form=group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" value="حفظ التغييرات" class="cart-btn" />
            </div>
            </div>




            </form>
            </div> 



<?php  

} else { 
    echo "<div class='container'>" ; 

    $theMsg =  "<div class= 'alert alert-danger'> there is no such ID </div> " ;
    redirectHome($theMsg ) ; 

    echo "</div>"; 
 } 
}

 // update page 

 elseif($do == 'update') { 

    echo "<h1 class='text-center'>  تحديث البيانات </h1>" ; 
    echo "<div class='container'>";

if ($_SERVER['REQUEST_METHOD'] == "POST"){

    // get variable from the form 

    $id = $_POST['userid']; 
    $user = $_POST['username']; 
    $email = $_POST['email']; 
    $full = $_POST['fullname']; 

    // trick for password 
    $pass = ''; 
    if(empty($_POST['newpassword'])){
        $pass = $_POST['oldpassword'];
    } else {
        $pass = sha1($_POST['newpassword']);
    }

//form validate 

$formErrors= array(); 

// if (strlen($user < 3 )){
    //     $formErrors[] = ' user cant be less than 4' ; 
    // }
    
    if (strlen($user > 20 )){
        $formErrors[] = 'user cant be more than 20' ; 
    }
    
    if(empty($user)){
        $formErrors[] = 'user cant be empty' ; 
    } 
    if(empty($full)){
        $formErrors[] = 'fullname cant be empty  ' ; 
    } if(empty($email)){
        $formErrors[] = 'email cant be empty' ; 
    } 
    
    foreach ($formErrors as $error) {
        echo '<div class="alert alert-danger">' .  $error . '</div>' ; 
    }

//chick if there is no error proced the update operation

if(empty($formErrors))  {

    $stmt2 = $con->prepare("SELECT * FROM users WHERE Username =? AND userID != ? "); 
    $stmt2-> execute(array( $user , $id )) ; 
    $rows = $stmt2->rowCount(); 
    if ($rows == 1 ) { 
        $theMsg =  "<div class='alert alert-danger'>this is user exist </div> " ; 
        redirectHome($theMsg , 'back' ) ;
    
    } else {


// update the data base with this info  
$stmt = $con->prepare("UPDATE users SET Username=?  , email= ? , fullname=? , password=? WHERE userID = ? ") ; 
$stmt->execute(array($user , $email , $full,$pass, $id ));

$theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() .'تم تحديث البيانات ' ; 
redirectHome($theMsg , 'back' ) ;
 

    }
}


} else {

  $theMsg =  "<div class='alert alert-danger'> sorry you cant browser update page </div> "; 

   redirectHome($theMsg) ; 


}

echo "</div>" ; 


  } elseif ($do == 'delete'){ // delet page 

    echo "<h1 class='text-center'>  حذف العملاء </h1>" ; 
    echo "<div class='container'>";


        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? 
        intval($_GET['userid']) : 0 ; 

        // select all data depend on this ID 

       $check = checkItem('userID' , 'users', $userid) ;



        if ($check > 0 ){   

            $stmt = $con->prepare("DELETE FROM users WHERE userID = :zuser"); 
            //bindParam = link with parameter 
            $stmt->bindParam(':zuser' , $userid); 

            $stmt->execute(); 

            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() ."تم الحذف "; 
            redirectHome($theMsg , 'back') ; 



        } else {

           $theMsg =  "<div class='alert alert-danger'> this id dosent exist </div>" ; 
            redirectHome($theMsg ) ; 

        }

echo '</div>';


}

    include $tpl.'footer.php';

} else {

    header('location:index.php') ; 
}