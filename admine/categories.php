    <?php 

    session_start() ; 

    if (isset($_SESSION['username'])) {

    $pageTitle = 'Categories' ; // for title 

    include 'init.php'; 

    $do =isset($_GET['do'])? $_GET['do'] : 'manage' ;  // اختصار دالة if



    if ($do == 'manage') { //manage member page


$sort='ASC' ; 
$sort_array = array ('ASC' , 'DESC') ;

if (isset ($_GET['sort']) && in_array($_GET['sort'] , $sort_array )) {

$sort = $_GET['sort'] ; 

  }

$stmt2 = $con->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY Ordering $sort ");
$stmt2->execute();
$cats = $stmt2->fetchAll();

?>


<h1 class="text-center"> ادآرة الاقسام  </h1>
<div class="container categories">
<div class="card">
    <div class="card-header">
   الاقسآم
    <div class="option float-left">
       الترتيب 
    <a class="<?php if($sort == 'ASC') { echo 'active' ; } ?>" href="?sort=ASC"> تصآعدي</a> |
       <a  class="<?php if($sort == 'DESC') { echo 'active' ; } ?>" href="?sort=DESC"> تنآزلي </a> 
       العرض : <span class="active" data-view="full">كآمل </span> |
          <span> كلآسيكي </span> 
    </div>

    

</div>
    <div class="card-body"> 
<?php 
foreach($cats as $cat){
    echo '<div class="cats">';
    echo '<div class="hidden-btn">'; 
    echo '<a href="categories.php?do=edit&catid=' . $cat['ID']. ' " class="  ed-btn"> تعديل </a>' ; 
    echo '<a href="categories.php?do=delet&catid=' . $cat['ID'] . ' " class="confirm  del-btn"> حذف </a>' ; 
    echo '</div>' ; 
    echo '<h3>'.$cat['Name'].'</h3>';
    echo '<div class="full-view">' ; 
    echo '<p>'; if($cat['Description'] == "") { echo "no discription" ; } else { echo $cat['Description'] ; } echo '</p>';

    $childCat = getAllFrom( "*" ,"categories" ," WHERE Parent = {$cat['ID']} " ," " , "ID" , "ASC" );
    foreach ($childCat as $c) {
        echo '<h5> child Categories </h5>' ; 
        echo '<li class="child-link">';
        echo '<a href="categories.php?do=edit&catid=' . $c['ID']. ' "> - '.$c['Name'] . '</a>';
        echo '<a href="categories.php?do=delet&catid=' . $c['ID'] . ' " class="show-delete confirm "> حذف </a>' ; 
        echo '</li>'; 
 
    }
    echo '</div>'; 
   
    echo '</div>';
    echo '<hr>' ; 



}


?>

    </div>
</div>

    <span class="  mt-5"> <a href="categories.php?do=add" class="cart-btn "> اضافة قسم  </a></span>


</div>


<?php 
    }elseif ( $do == 'add') { ?>

            <h1 class="text-center"> اضافة قسم جديد </h1>
            <form class="form-horizontal" action="?do=insert" method="POST">

            <div class=" container">
            <!-- name field  -->
            <div class="form-group">
            <label class="col-sm-2 col-form-label"> اسم القسم </label>
                <div class="col-sm-10 col-md-5">
                    <input type="text" name="name"  class="form-control" autocomplete="off" required="required" placeholder="اسم القسم " />
            </div>
            </div>
            <!-- Discription field  -->
            <div class="form-group">
            <label class="col-sm-2 col-form-label"> الوصف </label>
                <div class="col-sm-10 col-md-5 ">
                    <input type="text" name="description" class="form-control" autocomplete="new-password" placeholder="وصف للقسم " >
                </div>
            </div>
            <!-- orderingfield  -->
            <div class="form-group">
            <label class="col-sm-2 col-form-label"> الترتيب </label>
                <div class="col-sm-10 col-md-5">
                    <input type="text" name="ordering"   class="form-control"  />
            </div>
            </div>
               <!-- parent field  -->

               <div class="form-group">
            <label class="col-sm-2 col-form-label"> القسم الاصلي  </label> <br>
             <select name="parent" class="mr-3"> 
             <option value="none">  لايوجد </option>
                 <?php 
                $allCats = getAllFrom( "*" , "categories" , "WHERE Parent=0 " , " " , "ID" , "ASC" );
                    foreach ($allCats as $cat) {
                  echo '<option value="'.$cat["ID"].' ">'. $cat["Name"] .' </option>';

                    }
                    ?>
             </select>
            </div>
      

            <br>
            <!-- submit  -->
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" value="اضافة القسم" class="cart-btn  " />
            </div>
            </div>



            </div> 

            </form>


    <?php 
    }elseif ( $do == 'insert') {

        if ($_SERVER['REQUEST_METHOD'] == "POST"){

            echo "<h1 class='text-center'>  Insert categories </h1>" ; 
            echo "<div class='container'>";
        
            // get variable from the form 
        
            $name= $_POST['name']; 
            $desc = $_POST['description']; 
            $parent = $_POST['parent']; 
            $order = $_POST['ordering']; 
           

        
       
    // check if categories  in data base ? 
    
            $check = checkItem("Name" , "categories" , $name );
            
            if ($check == 1) {
                $theMsg =  '<div class="alert alert-danger"> sorry this categories is exist </div>' ; 
            
                redirectHome($theMsg , 'back') ; 
            
            } else { 
            
            
                    // insert user info in database 
                    $stmt = $con->prepare(" INSERT INTO categories(Name , Description , Parent , Ordering  )
                                                        VALUES(:zname , :zdesc , :zparent , :zorder  ) ");
            
                    $stmt->execute(array(
            
                        // value from top of $name =  $_POST['name]... etc 
                        'zname' => $name, 
                        'zdesc' => $desc , 
                        'zparent' => $parent , 
                        'zorder' => $order 
                     
                    
            
                    ));
                    
                
                    $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() .'done ' ; 
            
                    redirectHome($theMsg , 'back' , 5 ) ; 
                }
                } 



    }elseif ( $do == 'edit') {

    // for secure for the cat id 

    $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? 
    intval($_GET['catid']) : 0 ; 
    // select all data depend on this ID 

    $stmt = $con->prepare("SELECT * FROM categories WHERE ID= ? "); 
    $stmt->execute(array($catid)); 
    $cat = $stmt->fetch(); // array of info from database for this category 

    $count = $stmt->rowCount(); // variable 


    if ($count > 0 ){   ?> 

        <!-- // if you have the same CATEGORIES ID ,, show the form  -->


            <h1 class="text-center"> تعديل القسم  </h1>
            <form class="form-horizontal" action="?do=update" method="POST">
                <input type="hidden" name="catid" value="<?php echo $catid ; ?> "> 

            <div class=" container">
            <!-- name field  -->
            <div class="form-group">
            <label class="col-sm-2 col-form-label"> اسم القسم </label>
                <div class="col-sm-10 col-md-5">
                    <input type="text" name="name"  class="form-control"  required="required" placeholder="الاسم الجديد للقسم " value="<?php echo $cat['Name'];  ?>" />
            </div>
            </div>
            <!-- Discription field  -->
            <div class="form-group">
            <label class="col-sm-2 col-form-label"> الوصف </label>
                <div class="col-sm-10 col-md-5 ">
                    <input type="text" name="description" class="form-control"  placeholder="وصف للقسم " value="<?php echo $cat['Description'];  ?>">
                </div>
            </div>
            <!-- orderingfield  -->
            <div class="form-group">
            <label class="col-sm-2 col-form-label"> الترتيب </label>
                <div class="col-sm-10 col-md-5">
                    <input type="text" name="ordering" class="form-control" value="<?php echo $cat['Ordering'];  ?>" />
            </div>
            </div>

            <!-- parent field -->
            <div class="form-group">
            <label class="col-sm-2 col-form-label"> القسم الاصلي  </label>
                <select name="parent"> 
                    <option value="none">  لايوجد </option>
                    <?php 
                        $allCats = getAllFrom( "*" , "categories" , "WHERE Parent=0 " , " " , "ID" , "ASC" );
                        foreach ($allCats as $c) {
                        echo '<option value="'.$c["ID"].' " ';
                        
                        if($cat["Parent"]== $c["ID"]){
                            echo 'selected' ; 
                            }; 
                        
                        echo '>'; 
                        
                        echo $c['Name'].' </option>';
                        }
                     ?>
                </select>
            </div>
           

            <br>
            <!-- submit  -->
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" value="حفظ التغييرات " class="cart-btn btn-lg" />
            </div>
            </div>

            </div> 
            </form>

        <script>   $('.confirm').click(function(){ return confirm('هل انت متأكد  ?'); }); </script> 

    <?php  

    } else { 
    echo "<div class='container'>" ; 

    $theMsg =  "<div class= 'alert alert-danger'> يوجد خطأ في القسم </div> " ;
    redirectHome($theMsg ) ; 
    echo "</div>"; 

    } 
    
    }elseif ( $do == 'update') {

    echo "<h1 class='text-center'> تعديل القسم  </h1>" ; 
    echo "<div class='container'>";

if ($_SERVER['REQUEST_METHOD'] == "POST"){

    // get variable from the form 

    $id = $_POST['catid']; 
    $name = $_POST['name']; 
    $desc = $_POST['description']; 
    $order = $_POST['ordering']; 
    $parent = $_POST['parent']; 
   


   
 

// update the data base with this info  
$stmt = $con->prepare("UPDATE categories SET Name=?  , Description= ? , Ordering=? , Parent=?   WHERE ID = ? ") ; 

$stmt->execute(array($name , $desc , $order,$parent , $id ));

$theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() .'تم التعديل  ' ; 
redirectHome($theMsg , 'back' ) ;
 



} else {

  $theMsg =  "<div class='alert alert-danger'> لايمكن الدخول للصفحة  </div> "; 
   redirectHome($theMsg) ; 
}

echo "</div>" ; 

    }elseif ( $do == 'delet') {


        echo "<h1 class='text-center'>  حذف القسم  </h1>" ; 
        echo "<div class='container'>";
    
    
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? 
            intval($_GET['catid']) : 0 ; 
    
            // select all data depend on this ID 
    
           $check = checkItem('ID' , 'categories', $catid) ;
    
    
    
            if ($check > 0 ){   
    
                $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid"); 
                //bindParam = link with parameter 
                $stmt->bindParam(':zid' , $catid); 
    
                $stmt->execute(); 
    
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() ."تم الحذف "; 
                redirectHome($theMsg , 'back') ; 
    
    
    
            } else {
    
               $theMsg =  "<div class='alert alert-danger'> يوجد خطأ في القسم  </div>" ; 
                redirectHome($theMsg ) ; 
    
            }
    
    echo '</div>';
    
    

    }

    include $tpl.'footer.php';

    } else {

    header('location:index.php') ; 
    exit() ; 
    }

    ob_end_flush(); 





