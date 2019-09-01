

<?php


session_start() ; 
$pageTitle = 'Home page' ;
include "init.php";


 if(isset($_SESSION['user'])){



    $do =isset($_GET['do'])? $_GET['do'] : 'manage' ;  // اختصار دالة if

    
    if ($do == 'manage') { 

$getUser = $con->prepare("SELECT * FROM users WHERE Username =? "); 
$getUser->execute(array($sessionUser)); 

$info = $getUser->fetch();
$userid = $info['userID'];


?>


<div class="my" style="border:solid;">
    <div class="container">
        <div class=" mt-3 mb-5">
         <!-- <div class="card-header bg-info"> المعلومات الشخصية </div> -->
            <div class="">
                <div class="row">
                    <div class="col-md-6">

                            <form class="form-horizontal" action="?do=update" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="userid"  value="<?php echo $userid ?>" /> 

                            <div class=" container">
                            <!-- name field  -->
                      
                            <div class="form-group">
                            <label class="col-md-8"> الاسم كامل</label>
                            <div class="col-sm-10 col-md-10 ">
                            <input type="text" name="fullname"  class="form-control"  required="required" value="<?php echo $info['fullname'];?>"
                             />
                            </div>
                            </div>

                            <!-- description -->
                            <div class="form-group">
                            <label class="col-md-8"> المدينة </label>
                            <div class="col-sm-10 col-md-10">
                            <input type="text" name="city"  class="form-control " value="<?php echo $info['city'];?>" 
                            />
                            </div>
                            </div>

                                <!-- description -->
                                <div class="form-group">
                            <label class="col-md-8"> المحافظة </label>
                            <div class="col-sm-10 col-md-10">
                            <input type="text" name="city2"  class="form-control " value="<?php echo $info['address'];?>" 
                             />
                            </div>
                            </div>

                            <!-- phone  -->
                            <div class="form-group">
                            <label class="col-md-8"> الجوال </label>
                            <div class="col-sm-10 col-md-10">
                            <input type="text" name="phone"  class="form-control live " autocomplete="off" required="required" value="<?php echo $info['phone'];?>"
                             />
                            </div>
                            </div>


                            <!-- notes field  -->
                        <div class="form-group">
                        <label class="col-sm-2 col-form-label"> ملاحظات اضافيه </label>
                        <div class="col-md-10">
                        <input type="textarea" name="notes"  class="form-control" rows="10" value="<?php echo $info['notes'];?>"
                        placeholder="ملاحظات اضافيه : المنتج . التوصيل ... " 
                        
                        />
                        </div>
                        </div>

                     


                            <br>
                            <!-- submit  -->
                            <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="حفظ" class="btn btn-dark " />
                            </div>
                            </div>
                            </div> 

                            </form>
                    </div>
                </div></div></div>
            </div>
        </div>

<br>

<?php 

 } elseif  ($do == 'update') {



 if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // get variable from the form 

    $id = $_POST['userid'];
    $fullname= $_POST['fullname']; 
    $city = $_POST['city']; 
    $city2 = $_POST['city2']; 
    $phone= $_POST['phone']; 
    $notes= $_POST['notes']; 


//form validate 

$formErrors= array(); 

// if (strlen($user < 3 )){
    //     $formErrors[] = ' user cant be less than 4' ; 
    // }
    
    if (strlen($fullname > 35 )){
        $formErrors[] = 'user cant be more than 35' ; 
    }
    
    if(empty($city)){
        $formErrors[] = 'city cant be empty' ; 
    } 
    if(empty($city2)){
        $formErrors[] = 'city cant be empty  ' ; 
    } if(empty($phone)){
        $formErrors[] = 'email cant be empty' ; 
    } 
    if(empty($notes)){
        $formErrors[] = 'email cant be empty' ; 
    } 
    
    foreach ($formErrors as $error) {
        echo '<div class="alert alert-danger">' .  $error . '</div>' ; 
    }

//chick if there is no error proced the update operation

if(empty($formErrors))  {

   


// update the data base with this info  
$stmt = $con->prepare("UPDATE users SET fullname=?  , city= ? , address=? , phone=? , notes=? WHERE userID = ? ") ; 
$stmt->execute(array($fullname , $city , $city2 , $phone , $notes ,  $id ));

$theMsg =  '<div class="alert alert-success">  تم التعديل  </div>';
redirectHome($theMsg , 4 ) ; 
 

}



 }

} else {

  $theMsg =  "<div class='alert alert-danger'> sorry you cant browser update page </div> "; 

   redirectHome($theMsg) ; 


}
 

 } // session user 
 else { echo 'لايمكنك تصفح هذه الصفحة .. ' ;

echo '<a href="login.php" style="color:green;"> انضم الينا </a>';
}

include $tpl . 'footer.php'; 

 ?>