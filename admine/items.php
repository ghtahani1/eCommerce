<?php 



ob_start() ; // output buffering start 

session_start() ; 

$pageTitle = 'المنتجات' ; // for title 

if (isset($_SESSION['username'])) {

    include 'init.php'; 
$do = '';
if (isset ($_GET['do'])) { // ex: www.page.php?do=tat
$do = $_GET['do']; 

} else { 
    $do = 'manage'; // main page
}


if ($do == 'manage') {

   
// make link between the table  by the JOIN 
$stmt = $con->prepare("SELECT 
     
         items.* ,
         categories.Name AS category_name 
     
    FROM 
         items 
    INNER JOIN
         categories 
    ON 
         categories.ID= items.Cat_ID
    
     
    ORDER BY 
       item_ID DESC 
     ");

     $stmt->execute(); 

     // assign to variable 

     $items = $stmt->fetchAll(); 
     
     if(!empty($items)){ 
     ?> 
        <h1 class="text-center">  ادآرة المنتجات  </h1>

    <div class=" container">
        <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>الصورة</td>
                        <td> الاسم </td>
                        <td> الوصف </td>
                        <td> السعر </td>
                        <td> القسم  </td>
                        <td> التحكم </td> 
                    </tr>

                <?php

                foreach($items as $item) {
                    echo "<tr>" ; 
                     echo "<td>" . $item['item_ID'] . "</td>" ; 
                     echo "<td>";
                     echo'<img src="../uploads/avatars/'.$item['Image'].' " style="width:50px ; height:50px;"> ';
                      echo "</td>" ; 
                     echo "<td>" . $item['Name']. "</td>" ;
                     echo "<td>" . $item['Description']. "</td>" ; 
                     echo "<td>" . $item['Price']. "</td>" ; 
                     echo "<td>" . $item['category_name']. "</td>" ; 
                     echo "<td> 
 
                     <a href='items.php?do=edit&itemid= "  . $item['item_ID'] . " '  class='ed-btn ml-1'> تعديل </a>
                     <a href='items.php?do=delete&itemid= "  . $item['item_ID'] . " 'class='del-btn confirm '> حذف</a> "; 
                     
                   

                      echo "</td>"; 

                    echo "</tr>"; 
                }

                ?>

         <!-- for confirm the delet btn  -->
        <script>   $('.confirm').click(function(){ return confirm('هل انت متأكد  ?'); }); </script> 
            
               </table>
     </div>
 
            <?php } else { echo " <div class='container' style='margin-top:200px;'> لايوجد منتجآت قم بأضافة منتج </div>   " ; } ?>
            <br> 
     <a href="items.php?do=add" class="cart-btn"><i class="fas fa-plus"></i> اضافة منتج جديد  </a>
     </div>

 <?php } elseif ( $do == 'add') {  ?>



<h1 class="text-center">  اضافة منتج جديد </h1>
<form class="form-horizontal" action="?do=insert" method="POST" enctype="multipart/form-data">
        <div class=" container">
            <div class="row">
                <div class="col-md-8">
        <!-- name field  -->
        <div class="form-group">
        <label class="col-sm-2 col-form-label"> الاسم</label>
        <div class="col-sm-10 col-md-7">
        <input type="text" name="name"  class="form-control" autocomplete="off" required="required" placeholder="اسم المنتج" />
        </div>
        </div>

        <!-- description -->
         <div class="form-group">
        <label class="col-sm-2 col-form-label"> الوصف </label>
        <div class="col-sm-10 col-md-7">
        <input type="text" name="description"  class="form-control" autocomplete="off"  placeholder="وصف المنتج" />
        </div>
        </div>

       <!-- price  -->
        <div class="form-group">
        <label class="col-sm-2 col-form-label"> السعر </label>
        <div class="col-sm-10 col-md-7">
        <input type="text" name="price"  class="form-control" autocomplete="off" required="required" placeholder="سعر المنتج  " />
        </div>
        </div>


     <!-- status  -->
        <div class="form-group">
        <label class="col-sm-2 col-form-label"> حالة المنتج  </label>
        <div class="col-sm-10 col-md-7">
                <select class="form-control" name="status">
                    <option value="0"> ...</option>
                    <option value="1"> new </option>
                    <option value="2"> like new </option>
                    <option value="3"> used </option>
                    <option value="4"> very old  </option>

                </select>
        </div>
        </div>



    <!-- categories -->
    <div class="form-group">
    <label class="col-sm-2 col-form-label"> القسم  </label>
    <div class="col-sm-10 col-md-7">
    <select class="form-control" name="categories">
    <option value="0"> ...</option>
            <?php 

            $allCats = getAllFrom( "*" , "categories" ,"WHERE Parent = 0 " , " ", "ID" );

            foreach ($allCats as $cat){
            echo "<option value=' " .$cat['ID'] . " '> " .$cat['Name'] . "</option>"; 
            $allChild = getAllFrom( "*" , "categories" ,"WHERE Parent = {$cat['ID']} " , " ", "ID" );
                foreach($allChild as $child ) {
                    echo "<option value=' " .$child['ID'] . " '> -- " .$child['Name'] . "</option>"; 

                }
            }

            ?>

    </select>
    </div>
    </div>

    <!-- tags  -->
    <div class="form-group">
        <label class="col-sm-2 col-form-label"> تاق </label>
        <div class="col-sm-10 col-md-7">
        <input type="text" name="tags"  class="form-control"   placeholder="افصل بين التاقات ب ، فاصلة  " />
        </div>
        </div>

        <br>
      
        </div>

        <!-- image -->
        <div class="col-md-4">
                <div class="form-group">
                <label class=" control-label"> صورة المنتج </label>
                    <div class="col-md-10 ">
                    <input type="file" name="avatar"  class="form-control" required="required" />
                    </div>
                </div>
        </div>

          <!-- submit  -->
          <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
        <input type="submit" value="اضافة المنتج " class="cart-btn " />
        </div>
        </div>

        </div>
        </div> 

</form>


<?php }
elseif ( $do == 'insert') {

    if ($_SERVER['REQUEST_METHOD'] == "POST"){

        echo "<h1 class='text-center'>  اضافة المنتج </h1>" ; 
        echo "<div class='container'>";


        $avatarName = $_FILES['avatar']['name'];
        $avatarSize = $_FILES['avatar']['size'];
        $avatarTmp	= $_FILES['avatar']['tmp_name'];
        $avatarType = $_FILES['avatar']['type'];


        // the extension allowded 

        $extensionAllowded = array("jpg","png","jpeg","gif");

        // get extension 
        $ext = explode('.', $avatarName);
        $avatarExtension = strtolower(end($ext)) ; 
    
        // get variable from the form 
    
        $name= $_POST['name']; 
        $desc = $_POST['description']; 
        $price = $_POST['price']; 
        $status = $_POST['status']; 
        $cat = $_POST['categories']; 
        $tags = $_POST['tags']; 



        $formErrors= array(); 

    
            
            if (empty($name)){
                $formErrors[] = 'يجب اضافة اسم للمنتج ' ; 
            }
            
            if(empty($desc)){
                $formErrors[] = 'يجب اضافة وصف للمنتج ' ; 
            } 
            if(empty($price)){
                $formErrors[] = 'يجب اضافة سعر للمنتج  ' ; 
            } 
            if($status == 0 ){
                $formErrors[] = 'اختر حالة المنتج ' ; 
            } 
            
             if($cat == 0 ){
                $formErrors[] = 'اختر القسم المناسب للمنتج ' ; 
            } 
            if($avatarSize > 4555000 ){
            $formErrors[] = 'size is big ' ; 
            }
            if(!empty($avatarExtension) && !in_array($avatarExtension ,$extensionAllowded )) {
            echo 'the type of picture is not allowded' ; 
            }
            
            foreach ($formErrors as $error) {
               $theMsg= '<div class="alert alert-danger">' .  $error . '</div>' ; 

                redirectHome($theMsg , 'back' , 5 ) ; 
            }
        

    if(empty($formErrors))  {

        $avatar = rand(0 , 100000).'_'.$avatarName ;    

        move_uploaded_file($avatarTmp , "../uploads/avatars//".$avatar ) ; 
        

        // insert user info in database 
        $stmt = $con->prepare(" INSERT INTO items(Name , Description , Price , Image , Status , Cat_ID  , Tags )
                                            VALUES(:zname , :zdesc , :zprice  , :zimage , :zstatus ,  :zcat  , :ztags ) ");

        $stmt->execute(array(

            // value from top of $name =  $_POST['name]... etc 
            'zname' => $name, 
            'zdesc' => $desc , 
            'zprice' => $price , 
            'zimage' => $avatar , 
            'zstatus' => $status , 
            'zcat' => $cat , 
            'ztags' => $tags

        ));
        
    
        $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() .'تمت اضافة المنتج  ' ; 

        redirectHome($theMsg , 'back' , 5 ) ; 
    
    }  
 

}
} elseif ( $do == 'edit') { // EDIT page  
        
// for secure for the user id 

$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? 
intval($_GET['itemid']) : 0 ; 
// select all data depend on this ID 

$stmt = $con->prepare("SELECT * FROM items WHERE item_ID= ?  "); 
$stmt->execute(array($itemid)); 
$item = $stmt->fetch(); // array of info from database for this user 

$count = $stmt->rowCount(); // variable 


if ($count > 0 ){   



        ?>


        <div class=" container">

        <h1 class="text-center">  تعديل المنتجات </h1>
        <form class="form-horizontal" action="?do=update" method="POST">
        <input type="hidden" name="itemid"  value="<?php echo $itemid ?>" /> 

        <!-- name field  -->
        <div class="form-group">
        <label class="col-sm-2 col-form-label">الاسم </label>
        <div class="col-sm-10 col-md-5">
        <input type="text" name="name"  class="form-control" autocomplete="off" required="required" placeholder="اسم المنتج"
        value="<?php echo $item['Name'] ;  ?> " />
        </div>
        </div>

        <!-- description -->
        <div class="form-group">
        <label class="col-sm-2 col-form-label"> الوصف </label>
        <div class="col-sm-10 col-md-5">
        <input type="text" name="description"  class="form-control" autocomplete="off"  placeholder="وصف للمنتج "
        value="<?php echo $item['Description'] ;  ?> " />
        </div>
        </div>

        <!-- price  -->
        <div class="form-group">
        <label class="col-sm-2 col-form-label"> السعر </label>
        <div class="col-sm-10 col-md-5">
        <input type="text" name="price"  class="form-control" autocomplete="off" required="required" placeholder="سعر المنتج  "
        value="<?php echo $item['Price'] ;  ?> "  />
        </div>
        </div>

       

        <!-- status  -->
        <div class="form-group">
        <label class="col-sm-2 col-form-label"> الحالة  </label>
        <div class="col-sm-10 col-md-5">
        <select class="form-control" name="status">
        <option value="0"> ...</option>
        <option value="1" <?php if ($item['Status'] == 1) { echo "selected" ; } ?> > new </option>
        <option value="2" <?php if ($item['Status'] == 2) { echo "selected" ; } ?>> like new </option>
        <option value="3" <?php if ($item['Status'] == 3) { echo "selected" ; } ?>> used </option>
        <option value="4" <?php if ($item['Status'] == 4) { echo "selected" ; } ?>> very old  </option>

        </select>
        </div>
        </div>

       

        <!-- categories -->
        <div class="form-group">
        <label class="col-sm-2 col-form-label"> القسم </label>
        <div class="col-sm-10 col-md-5">
        <select class="form-control" name="categories">
        <option value="0"> ...</option>
            <?php 

            $stmt = $con->prepare("SELECT * FROM categories");
            $stmt->execute(); 
            $cats = $stmt->fetchAll(); 
            foreach ($cats as $cat){
            echo "<option value=' " .$cat['ID'] . " ' " ; 
            if ($item['Cat_ID'] == $cat['ID']) { echo "selected" ; }     
            echo " > " .$cat['Name'] . "</option>"; 
            }

            ?>

        </select>
        </div>
        </div>

        <!-- tags field -->
         <div class="form-group">
        <label class="col-sm-2 col-form-label"> تاق  </label>
        <div class="col-sm-10 col-md-5">
        <input type="text" name="tags"  class="form-control" 
          placeholder="separate with comma (,) " 
          value="<?php echo $item['Tags'] ; ?>"
          />
        </div>
        </div>


        <br>
        <!-- submit  -->
        <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
        <input type="submit" value="add item" class="cart-btn " />
        </div>
        </div>
        </form>


<?php 


                        $stmt = $con->prepare("SELECT 
                                comments.* , users.Username
                                FROM 
                                comments 
                                INNER JOIN 
                                users 
                                ON 
                                users.userID = comments.user_ID 
                                WHERE 
                                item_ID = ? 
                              ");
     $stmt->execute(array("$itemid")); 

     // assign to variable 

     $rows = $stmt->fetchAll(); 

     if (!empty($rows)) {
     
     
     ?> 
        <h1 class="text-center">  Manage <?php echo $item['Name'] ;  ?> Comments </h1>

        <div class=" container">
        <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td> comments  </td>
                        <td> user name </td> 
                        <td> added date </td>
                        <td> control </td> 
                    </tr>

                <?php

                foreach($rows as $row) {
                    echo "<tr>" ; 
                     echo "<td>" . $row['Comment']. "</td>" ;
                     echo "<td>" . $row['user_ID']. "</td>" ; 
                     echo "<td>".$row['comment_Date'] ."</td>" ; 
                     echo "<td> 
 
                     <a href='comments.php?do=edit&comid= "  . $row['c_ID'] . " '  class='btn btn-success'> edit </a>
                     <a href='comments.php?do=delete&comid= "  . $row['c_ID'] . " 'class='btn btn-danger confirm '> Delet </a> "; 
                    

                     //if regstatus = 0 show the button .. we can use it any where 
                     if ($row['Status'] == 0){
                        echo  "<a href='comments.php?do=activate&comid=" . $row['c_ID'] . " '  class='btn btn-info'> Approve </a>";
                     }


                      echo "</td>"; 

                    echo "</tr>"; 
                }

                ?>

         <!-- for confirm the delet btn  -->
        <script>   $('.confirm').click(function(){ return confirm('Are you sure ?'); }); </script> 
            
               </table>
     </div>

     </div>

       </div> 

 <?php 
     }

} else { 

echo "<div class='container'>" ; 

$theMsg =  "<div class= 'alert alert-danger'> there is no such ID </div> " ;
redirectHome($theMsg ) ; 

echo "</div>"; 
} 


} elseif($do == 'update') {

    if ($_SERVER['REQUEST_METHOD'] == "POST"){

        echo "<h1 class='text-center'> تعديل المنتج</h1>" ; 
        echo "<div class='container'>";
    
        // get variable from the form 
    
        $id = $_POST['itemid']; 
        $name= $_POST['name']; 
        $desc = $_POST['description']; 
        $price = $_POST['price']; 
        $status = $_POST['status']; 
        $cat = $_POST['categories']; 
        $tags = $_POST['tags']; 


        $formErrors= array(); 

    
            
            if (empty($name)){
                $formErrors[] = 'name cant be empty ' ; 
            }
            
            if(empty($desc)){
                $formErrors[] = 'description cant be empty' ; 
            } 
            if(empty($price)){
                $formErrors[] = 'price cant be empty  ' ; 
            }  
            if($status == 0 ){
                $formErrors[] = 'you should choose status ' ; 
            } 
            
             if($cat == 0 ){
                $formErrors[] = 'you should choose categories ' ; 
            } 
            
            foreach ($formErrors as $error) {
               $theMsg= '<div class="alert alert-danger">' .  $error . '</div>' ; 

                redirectHome($theMsg , 'back' , 5 ) ; 
            }
        

    if(empty($formErrors))  {


        // insert user info in database 
        $stmt = $con->prepare("  UPDATE items SET Name=? , Description=? , Price=?  , Status=? , Cat_ID=? ,  Tags=? WHERE item_ID=? ");
        $stmt->execute(array($name , $desc , $price , $status ,  $cat  , $tags, $id ));
       
    
        $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() .'done ' ; 

        redirectHome($theMsg , 'back' , 5 ) ; 
    
    }  

} else {

    $theMsg =  "<div class='alert alert-danger'> sorry you cant browser update page </div> "; 
  
     redirectHome($theMsg) ; 
  
  
  }
} elseif ($do == 'delete') {

    echo "<h1 class='text-center'>  حذف المنتج </h1>" ; 
    echo "<div class='container'>";


        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? 
        intval($_GET['itemid']) : 0 ; 

        // select all data depend on this ID 

       $check = checkItem('item_ID' , 'items', $itemid) ;



        if ($check > 0 ){   

            $stmt = $con->prepare("DELETE FROM items WHERE item_ID = :zitem"); 
            //bindParam = link with parameter 
            $stmt->bindParam(':zitem' , $itemid); 

            $stmt->execute(); 

            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() ."تم حذف المنتج  "; 
            redirectHome($theMsg) ; 

        } else {

           $theMsg =  "<div class='alert alert-danger'> this id dosent exist </div>" ; 
            redirectHome($theMsg ) ; 

        }

echo '</div>';

} 


include $tpl.'footer.php';


}else { 
    echo ' error no page ' ; 
}


