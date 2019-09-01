<?php


session_start() ; 
$pageTitle = 'create new item ' ;


include "init.php";

if(isset($_SESSION['user'])){


    if($_SERVER['REQUEST_METHOD'] == 'POST'){


        $avatarName = $_FILES['avatar']['name'];
        $avatarSize = $_FILES['avatar']['size'];
        $avatarTmp	= $_FILES['avatar']['tmp_name'];
        $avatarType = $_FILES['avatar']['type'];


        // the extension allowded 

        $extensionAllowded = array("jpg","png","jpeg","gif");

        // get extension 
        $ext = explode('.', $avatarName);
        $avatarExtension = strtolower(end($ext)) ; 




        $name= filter_var($_POST['name'] , FILTER_SANITIZE_STRING); 
        $desc = filter_var($_POST['description'] , FILTER_SANITIZE_STRING); 
        $price =filter_var( $_POST['price'] ,  FILTER_SANITIZE_NUMBER_INT); 
        $country = filter_var($_POST['country'] , FILTER_SANITIZE_STRING); 
        $status = filter_var($_POST['status'] , FILTER_SANITIZE_NUMBER_INT); 
        $cat = filter_var($_POST['categories'] , FILTER_SANITIZE_NUMBER_INT); 
        $tags = filter_var($_POST['tags'] , FILTER_SANITIZE_STRING); 



        $formErrors = array () ; 

if(strlen($name) < 4 ) {
    $formErrors[] = 'the Title Must Be More Than 4 Char ' ; 
}
if(strlen($desc) < 10 ) {
    $formErrors[] = 'the Description Must Be More Than 10 Char ' ; 
}

if(strlen($country) < 2 ) {
    $formErrors[] = 'the country Must Be More Than 2 Char ' ; 
}

if(empty($price)) {
    $formErrors[] = 'the Price should not empty ' ; 

}

if(empty($status)) {
    $formErrors[] = 'choose status  ' ; 

}if(empty($cat)) {
    $formErrors[] = ' choose categories ' ; 

}
if($avatarSize > 4555000 ){
    $formErrors[] = 'size is big ' ; 
}
if(!empty($avatarExtension) && !in_array($avatarExtension ,$extensionAllowded )) {
    echo 'the type of picture is not allowded' ; 
}


if(empty($formErrors)) {

$avatar = rand(0 , 100000).'_'.$avatarName ;    

move_uploaded_file($avatarTmp , "uploads/avatars//".$avatar ) ; 


  // insert user info in database 
  $stmt = $con->prepare(" INSERT INTO items(Name , Description , Price , Country_Made,Image , Status , Add_Date , Cat_ID , Member_ID , Tags )
  VALUES(:zname , :zdesc , :zprice , :zcountry , :zimage , :zstatus , now() , :zcat , :zmember , :ztags ) ");

$stmt->execute(array(

// value from top of $name =  $_POST['name]... etc 
'zname' => $name, 
'zdesc' => $desc , 
'zprice' => $price , 
'zcountry'=> $country , 
'zimage'=> $avatar , 
'zstatus' => $status , 
'zcat' => $cat , 
'zmember' =>  $_SESSION['uID'],
'ztags' => $tags

// user id from the session in login page 

));


if($stmt) {
$sucsses = ' your item has addes ' ; }

}
    }

?>

<div class="my-ads">
    <div class="container">
        <div class="card mt-3 mb-5">
         <div class="card-header bg-info"> <?php echo $pageTitle ?> </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">

                            <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">

                            <div class=" container">
                            <!-- name field  -->
                            <div class="form-group">
                            <label class="col-md-8"> Name</label>
                            <div class="col-sm-10 col-md-10 ">
                            <input type="text" name="name"  class="form-control live "  autocomplete="off" required="required" placeholder="Name of the Item"
                            data-class=".live-title" />
                            </div>
                            </div>

                            <!-- description -->
                            <div class="form-group">
                            <label class="col-md-8"> Description </label>
                            <div class="col-sm-10 col-md-10">
                            <input type="text" name="description"  class="form-control live" autocomplete="off"  placeholder="add description "
                            data-class=".live-desc"  />
                            </div>
                            </div>

                            <!-- price  -->
                            <div class="form-group">
                            <label class="col-md-8"> Price </label>
                            <div class="col-sm-10 col-md-10">
                            <input type="text" name="price"  class="form-control live " autocomplete="off" required="required" placeholder="price of item "
                            data-class=".live-price" />
                            </div>
                            </div>

                            <!-- country  -->
                            <div class="form-group">
                            <label class="col-md-8"> Country </label>
                            <div class="col-sm-10 col-md-10">
                            <input type="text" name="country"  class="form-control" autocomplete="off"  placeholder="country of made" />
                            </div>
                            </div>

                            <!-- status  -->
                            <div class="form-group">
                            <label class="col-md-8"> Status </label>
                            <div class="col-sm-10 col-md-10">
                            <select class="form-control" name="status">
                            <option value=""> ...</option>
                            <option value="1"> new </option>
                            <option value="2"> like new </option>
                            <option value="3"> used </option>
                            <option value="4"> very old  </option>

                            </select>
                            </div>
                            </div>


                            <!-- categories -->
                            <div class="form-group">
                            <label class="col-md-8"> Categories </label>
                            <div class="col-sm-10 col-md-10">
                            <select class="form-control" name="categories">
                            <option value=""> ...</option>
                            <?php 
                               $cats = getAllFrom('*' , 'categories','','' ,'ID');
                             
                                foreach ($cats as $cat){
                                echo "<option value=' " .$cat['ID'] . " '> " .$cat['Name'] . "</option>"; 
                                }

                            ?>

                            </select>
                            </div>
                            </div>


                            <!-- tags field  -->
                        <div class="form-group">
                        <label class="col-sm-2 col-form-label"> Tags </label>
                        <div class="col-sm-10 col-md-5">
                        <input type="text" name="tags"  class="form-control" 
                        placeholder="separate with comma (,) " 
                        
                        />
                        </div>
                        </div>

                              <!-- picture field  -->
                        <div class="form=group">
                        <label class="col-sm-2 control-label"> picture </label>
                        <div class="col-sm-10 col-md-5">
                        <input type="file" name="avatar"  class="form-control" required="required" />
                        </div>
                        </div>


                            <br>
                            <!-- submit  -->
                            <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="add item" class="btn btn-primary " />
                            </div>
                            </div>
                            </div> 

                            </form>
                    </div>


                    <div class="col-md-4">
                        <div class="card mb-3 live-preview">
                          <?php 
                            $items = getAllFrom('Image' , 'items','','' ,'item_ID');
                                                        

                            if( empty($items['Image'])) {
                        echo '<img  class="img-fluid  card-img-top"
                        src="uploads/avatars/52817_coffee-logo-.jpg" alt="pic">'; 

                     }else {
                         echo "<img src='uploads/avatars/" . $items['Image'] . " ' alt='pic'>"; 
                     }
                    
                            ?>
                         
                                <div class="card-body">

                                <h3 class="live-title">Title </h3>
                                <p class="live-desc"> Description </p> 
                                 $<span class="price live-price"> 0 </span>
                                </div>
                        </div>

                    </div>
                </div>

                        <!-- looping in errors  -->
                        <?php 

                        if(!empty($formErrors)){
                        foreach($formErrors as $error ){
                            echo  '<div class="alert alert-danger">' . $error . '</div>';
                        }
                        } 

                        if (isset($sucsses)) {
                            echo '<div class="alert alert-success">' . $sucsses . '</div>'; 
                        }

                        ?>
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