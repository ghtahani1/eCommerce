<?php


session_start() ; 
$pageTitle = 'المنتجات' ;


include "init.php";
?>

<div class="container">
    <nav class="nav3">
<a href="index.php"> الرىيسيه </a> >> <a href="index.php"> المنتج </a>
    </nav>
</div>



<?php
$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? 
intval($_GET['itemid']) : 0 ; 
// select all data depend on this ID 

$stmt = $con->prepare("SELECT items.* , categories.Name as catName
                        FROM items
                        INNER JOIN 
                        categories
                        ON
                        categories.ID = items.Cat_ID
                        
                         WHERE item_ID= ? 
                         "); 

$stmt->execute(array($itemid)); 

$count = $stmt->rowCount(); 

if($count > 0) {


$item = $stmt->fetch();  
?>


<div class="container detel "> 
    <div class="row">
        <div class="col-md-5 images">
            <?php 

                    if( empty($item['Image'])) {
                        echo '<img  class="img-fluid  card-img-top"
                        src="uploads/avatars/52817_coffee-logo-.jpg" alt="pic">'; 

                    }else {
                        echo "<img class='img-fluid  card-img-top' src='uploads/avatars/" . $item['Image'] . " ' alt='pic'>"; 
                    }

                ?>
        </div>
        <div class="col-md-7">
            
            <h2><?php echo $item['Name'] ; ?></h2>
            <br> <h3 class="price2"> SR <?php echo $item['Price'] ?></h3> <br>
            <p> القسم : <a href="categories.php?catid= <?php echo $item['Cat_ID'] ?>"> <?php echo $item['catName'] ?> </a></p>
            <p>  حالة التوفر :   <span class="green"> متوفر </span> </p> 
                       
            <p>   الكمية: </p>


                 <!--======= quantity ======== -->


            <div class="center">
               <div class="input-group">
                    

                  <input type="number"  name="qty[]" value = "1"  class="form-group input-number" min="1" max="5" style="
                  width: 100%">

               </div>
            </div>


                 <!--=========== quantity ======== -->

            
<br><a href="add-to-cart.php?itemid=<?php echo $item['item_ID'];?>"> <button class="btn btn-dark btn-lg mb-3">اضف للسلة  </button> </a>


<br>

            <?php  
            $allTags = explode(',' , $item['Tags']) ; 
            foreach( $allTags as $tag ){
              $tag =   str_replace(' ' , '' , $tag) ; 
              if(!empty($tag)) {
                  echo '<p> التصنيفات : ';
              echo '<a href="tags.php?name= '. $tag . ' ">' . $tag . '</a> | </p>';

                      }
                  }
                


            ?>




        </div>
    </div>

    <hr>

    

</div>



<!-- ============== Start tabs =================  -->

<div class="container mt-3">
<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
  <a class="nav-item nav-link active" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true">الشحن والدفع </a>
    <a class="nav-item nav-link " id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="false">التقييمات</a>
  </div>
</nav>


<div class="tab-content" id="nav-tabContent">


<div class="tab-pane fade show active " id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">

<p class="mt-4"> تفاصيل الشحن و  سياسة الشحن والاسترجاع </p>

</div>

  <div class="tab-pane fade " id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
      

  <!--============= view comments  -->


    <?php 
        $stmt = $con->prepare("SELECT * FROM comments WHERE Status=1 AND item_ID=? ");

        $stmt->execute(array($item['item_ID'])); 


        $comments = $stmt->fetchAll(); 

        if(!empty($comments)){
        foreach($comments as $comment){
                echo '<div class="row mt-4">';
                echo '<div class="col-sm-2 comments text-center">
                <img class="img-fluid rounded-circle mx-auto" src="20752_2.jpg" />
                <h4>'. $comment['vis'] .

                '</h4></div>' ; 
                echo '<div class="col-sm-10 lead">' .  $comment['Comment'] . '<br>' ; 
                echo '</div>';
                echo '</div>';
                echo "<hr>" ;
        }
        }else {
        echo '<p class="mt-3"> لايوجد تعليق حتى الآن </p>'; 
        }
    ?>



<!-- ==============send comments ================-->

<!-- Button trigger modal -->
<br> <button type="button" class="btn btn-dark mt-3" data-toggle="modal" data-target="#exampleModal">
 كتابة تعليق 
</button>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> التعليقات</h5>
        <button type="button" class="close float-left" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

      <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $comment = filter_var($_POST['comment'] , FILTER_SANITIZE_STRING ) ; 
    $username = filter_var($_POST['username'] , FILTER_SANITIZE_STRING ) ; 

    $itemid = $item['item_ID'];
    // $userid = $_SESSION['uID'];

    if(!empty($comment)) {
        if(strlen($comment) > 10 ) {

      $stmt =  $con->prepare(" INSERT INTO 
        comments(Comment , Status , comment_Date , item_ID , vis ) 
            VALUES(:zcomment , 0 , NOW() , :zitem  , :zvis)") ;

            $stmt->execute(array(

                'zcomment' => $comment ,
                'zitem' => $itemid ,
                'zvis' => $username   

            )); 

            echo '<div class="alert alert-success"> تم ارسال التعليق شكرا لك   </div> '; 

    } else { 
        
        echo '<div class="alert alert-danger"> يجب كتابة تعليق يحتوي على اكثر من 10 حروف  </div> '; 
    }
} 
}    

    ?>

      <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?itemid='. $item['item_ID'] ?>">
            <input class="form-control mb-3 " name="username" placeholder="اكتب اسمك" >
            <textarea name="comment" class="form-control mb-3 " placeholder=" :) اكتب تعليقك  " required > </textarea>
      </div>
      <div class="modal-footer">
  
        <input class="btn btn-primary mb-3" type="submit" value="ارسال"> 
        </form>

      </div>
    </div>
  </div>
</div>

<!-- ======= end comment -->

  </div>



</div>
</div>
  


<?php
} else {
    echo 'there is no item by this ID or Item waiting approval  ';
}
include $tpl.'footer.php';
?>