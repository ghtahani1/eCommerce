<?php

session_start();
include "init.php"; ?>


            <?php  
 
            if (isset($_GET['name']) ) {
                $tags = $_GET['name'];
                echo '<div class="container"> ' ;        
                echo '<h1 class="text-center">'. $tags .'</h1>'; 
                echo '     <div class="row"> ' ;      

                $allItems = getAllFrom("*", "items", "where Tags like '%$tags%' " , "AND Approve = 1", "Item_ID");

              foreach ($allItems as $item) { 
                    echo '<div class="col-sm-6 col-md-4">';
                        echo '<div class="card mb-3">';
                        echo '<img class="img-fluid card-img-top" src="noAvatar-1.png" />'; 
                        echo '<div class="card-body">' ;

                            echo '<h3><a href="items.php?itemid=' .$item['item_ID'].'  ">' .$item['Name'] . '</a></h3>'; 
                            echo '<p>' .$item['Description'] . '</p>'; 
                            echo '<span class="price"> $' .$item['Price'] . '</span>';

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