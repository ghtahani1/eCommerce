<?php 
session_start() ; 

$pageTitle = 'معلومات الشراء' ; // for title 
include 'init.php'; 

?>


<div class="container">
<h1 class="text-center"> انهاء الطلب  </h1>

<div class="row">

<?php if (isset($_SESSION['user'])) { ?>
    <div class="col-md-8">
   <p> اذا كنت  تريد تعديل معلومآت الشحن اظغط هنا  </p>
   <a href="address.php"><button class="btn btn-dark"> تعديل معلوومات الشحن  </button></a>
<br> 
   <a href="#"><button class="btn btn-danger mt-5"> اتمام الشرآء  </button></a>

</div>



<?php } else { ?>

    <div class="col-md-8">
      <div class="checkout">

       <div class="card">
        <p> تسجيل الدخول او المتابعة ك زآئر : </p> <br> 
        <button class="btn btn-dark"><a href="login.php" style="color:#fff;"><h5> تسجيل الدخول </h5></a></button>   <br>
        <button class="btn btn-dark vist-info "><h5> المتابعة ك زائر  </h5></button>  <br>
      
       </div>
    </div>

        <br>
<!-- ===================== info for visetors ================== -->

   


<div class="my-ads" style="border:solid;">
    <div class="container">
        <div class=" mt-3 mb-5">
         <!-- <div class="card-header bg-info"> المعلومات الشخصية </div> -->
            <div class="">
                <div class="row">
                    <div class="col-md-12">

                            <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">

                            <div class=" container">
                            <!-- name field  -->
                            <div class="form-group">
                            <label class="col-md-8"> الاسم الاول</label>
                            <div class="col-sm-10 col-md-10 ">
                            <input type="text" name="fullname"  class="form-control"   required="required" placeholder="الاسم كامل "
                             />
                            </div>
                            </div>
                       

                            <!-- city -->
                            <div class="form-group">
                            <label class="col-md-8"> المدينة </label>
                            <div class="col-sm-10 col-md-10">
                            <input type="text" name="city"  class="form-control "  
                            />
                            </div>
                            </div>

                                <!-- description -->
                                <div class="form-group">
                            <label class="col-md-8"> المحافظة </label>
                            <div class="col-sm-10 col-md-10">
                            <input type="text" name="city2"  class="form-control "   
                             />
                            </div>
                            </div>

                            <!-- price  -->
                            <div class="form-group">
                            <label class="col-md-8"> الجوال </label>
                            <div class="col-sm-10 col-md-10">
                            <input type="text" name="phone"  class="form-control live " autocomplete="off" required="required" placeholder="رقم الجوال  "
                             />
                            </div>
                            </div>

                            <!-- country  -->
                            <div class="form-group">
                            <label class="col-md-8"> البريد الالكتروني </label>
                            <div class="col-sm-10 col-md-10">
                            <input type="email" name="email"  class="form-control"  placeholder="بريدك الحالي ليصلك رقم الطلب " />
                            </div>
                            </div>





                            <!-- tags field  -->
                        <div class="form-group">
                        <label class="col-sm-2 col-form-label"> ملاحظات اضافيه </label>
                        <div class="col-md-10">
                        <input type="textarea" name="notes"  class="form-control" rows="10"
                        placeholder="ملاحظات اضافيه : المنتج . التوصيل ... " 
                        
                        />
                        </div>
                        </div>

                     


                            <br>
                            <!-- submit  -->
                            <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value=" اتمام الشراء" class="btn btn-dark " />
                            </div>
                            </div>
                            </div> 

                            </form>
                    </div>
                </div>
              </div>
            </div>
            </div>
        </div>
            
<!-- ===================== end info for visetors ================== -->

<br>

<?php

if ($_SERVER['REQUEST_METHOD'] == "POST"){

    $fullname = $_POST['fullname']; 
    $city= $_POST['city']; 
    $city2 = $_POST['city2']; 
    $phone = $_POST['phone']; 
    $email= $_POST['email']; 
    $notes = $_POST['notes']; 


        $formErrors= array(); 


        if (strlen($fullname > 40 )){
            $formErrors[] = 'يجب ان يكون الاسم اقل من 40 حرف ' ; 
        }
        if(empty($city)){
            $formErrors[] = 'اكتب اسم مدينتك ' ; 
        } 
        if(empty($city2)){
            $formErrors[] = 'password cant be empty' ; 
        } 
        if(empty($phone)){
            $formErrors[] = 'fullname cant be empty  ' ; 
        } if(empty($email)){
            $formErrors[] = 'email cant be empty' ; 
        } if(empty($notes)){
            $formErrors[] = 'email cant be empty' ; 
        }
      
        
        foreach ($formErrors as $error) {
            echo '<div class="alert alert-danger">' .  $error . '</div>' ; 
        }
        
        //chick if there is no error proced the update operation
        
        if(empty($formErrors))  {
            
 
    
    
            // insert user info in database 
            $stmt = $con->prepare(" INSERT INTO clients (fullname , city , city2 , phone , email ,  Date , notes )
                                                VALUES(:zname , :zcity , :zcity2 , :zphone , :zemail , now() , :znotes) ");
    
            $stmt->execute(array(
    
                // value from top of $user =  $_POST['username]... etc 
                'zname' => $fullname , 
                'zcity' => $city , 
                'zcity2'=> $city2 , 
                'zphone' => $phone ,
                'zemail' => $emmail ,
                'znotes' => $notes 

                
    
            ));
            
        
            $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() .'تمت اضافة المعلوومات  ' ; 
    
            // redirectHome($theMsg , 'back' , 9 ) ; 
        }
          
        
    
        
        
        
        } else {
        
        //     echo "<div class='container'>" ; 
        //   $theMsg =  "<div class='alert alert-danger'> sorry you cant browser this page </div> "; 
    
        //     redirectHome($theMsg , 'back' , 5 ) ; 
    
        //     echo "</div>" ; 
    

}



?>










     </div> 

<?php } 


if(!empty($_SESSION['cart'])){
    $items = $_SESSION['cart'] ; 
    
    $wherein =implode("','", $items);
    
    // echo $wherein;
    
    $stmt = $con->prepare("SELECT * FROM items WHERE  item_ID  IN ('.$wherein.'); ");
    
    $stmt->execute(array());

    $items = $stmt->fetchAll(); 
    
?> 
   

     <div class="col-md-4 table-1">
        <table class="table">

	<thead class="bg-light">
		<tr>
			<th class="product-name">المنتج</th>
			<th class="product-total">السعر</th>
		</tr>
    </thead>
    
        <tbody>
       <?php
       $total = 0;
       foreach($items as $item) { 

        echo '<tr>' ; 
        echo '<td>'.$item['Name'] ; 
               echo '<td>';
          echo '<span class="amount">'. $item['Price']  .'<span class=""> ر.س </span></span>';
               echo' </td>';
               echo'  </tr>';
              
              $total = $item['Price']+ $total ;
             
        } ?> 
        </tbody>
	<tfoot class="bg-light">
		<tr>
			<th>الإجمالي</th>
			<td><strong><span class=""><?php echo $total  ?> <span class=""> ر.س </span></span></strong> </td>
		</tr>
	</tfoot>
</table>
            
        </div>



    </div>
</div>



<?php 
}
include $tpl . 'footer.php'; 

 ?>