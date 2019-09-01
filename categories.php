<?php

session_start();
include "init.php"; ?>

<div class="container">
    <h1 class="text-center"> 
    <?PHP 

$categories = getAllFrom('*' , 'categories' , 'WHERE Parent=0 ' ,'' ,  'ID' , 'ASC') ; 
foreach ($categories as $cat) {
 if($_GET['catid'] == $cat['ID']){
     echo $cat['Name']; 
 }
}
?>
 </h1> 
    <div class="row"> 

            <?php  
 
            if (isset($_GET['catid']) && is_numeric($_GET['catid'])) {
                $category = $_GET['catid'];
                $allItems = getAllFrom("*", "items", "where Cat_ID = {$category}", "", "Item_ID");

              foreach ($allItems as $item) { 
                    echo '<div class="col-sm-6 images col-md-4">';
                        echo '<div class="card mb-3">';

                        if( empty($item['Image'])) {
                            echo '<img  class="img-fluid  card-img-top"
                            src="uploads/avatars/52817_coffee-logo-.jpg" alt="pic">'; 
    
                        }else {
                            echo "<img style='height:288px' class='img-fluid  card-img-top' src='uploads/avatars/" . $item['Image'] . " ' alt='pic'>"; 
                        }
                        echo '<div class="card-body">' ;

                            echo '<h3><a href="items.php?itemid=' .$item['item_ID'].'  ">' .$item['Name'] . '</a></h3>'; 
                            echo '<p>' .$item['Description'] . '</p>'; 
                            echo '<span class="price2"> $' .$item['Price'] . '</span>';
                            echo '<a href="add-to-cart.php?itemid='. $item['item_ID'] . ' "> <button class="cart-btn">اضف للسلة  </button> </a>';
                        echo '</div>' ; 
                        echo '</div>' ; 
                    echo '</div>' ; 
                
                } 
            } else { echo ' there is no request  ' ; }
            ?> 
    </div> 
</div>


<?php
include $tpl.'footer.php';
?>