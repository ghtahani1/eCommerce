<?php 


ob_start() ; // output buffering start 

session_start() ; 

if (isset($_SESSION['username'])) {

    $pageTitle = 'لوحة التحكم' ; // for title 

    include 'init.php'; 


    //func for latest users div 
    $latestUsers = 5 ;
    $theLatestUser = getLatest("*" , "users" , "userID" , $latestUsers); 

    $latestItems = 5 ; 
    $theLatestItems = getLatest("*" , "items" , "item_ID" , $latestItems); 

    $latestCom = 3 ; 
    $theLatestCom = getLatest("*" , "comments" , "c_ID" , $latestCom); 


?>


 <!-- start dashboard page  -->




<div class="container home-stats">
    <h1> لوحة التحكم  </h1>
    <div class="row">

    <div class="col-md-3">
        <div class="stat" style="background-color: #ffc107">
            <span><a href="member.php"> <?php echo countItems('userID' , 'users') ; ?> </a> </span>
            مجموع العملاء 
        </div>
    </div>

   
        
        <div class="col-md-3">
                <div class="stat" style="background-color: #dc3545">
                    <span><a href="items.php"> <?php echo countItems('item_ID' , 'items') ; ?> </a> </span>
                    مجموع المنتجات 

                </div>
            </div> 
            
            
            <div class="col-md-3">
                    <div class="stat" style="background-color: #007bff">
                        <span><a href="comments.php"> <?php echo countItems('c_ID' , 'comments') ; ?> </a> </span>
                        مجموع التعليقات

                    </div>
                </div>  

                <div class="col-md-3">
                <div class="stat" style="background:#2c1a91;" >
                   <span><a href="items.php"> <?php echo countItems('item_ID' , 'items') ; ?> </a> </span>
                   مجموع المشتريات  
                </div>
            </div> 


    </div>
</div>



<div class="container latest">
    
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-users"></i> اخر <?php echo $latestUsers ?> عملاء 
                </div>
                <div class="card-body">
                    <ul class="list-unstyled latest-users">
                        <?php 

                    if(!empty($theLatestUser)) {
                        foreach ($theLatestUser as $user ){
                            echo "<li>". $user['Username'] .
                             " <a href='member.php?do=edit&userid=" . $user['userID'] . " '> 
                             <span class='ed-btn float-left'> تعديل " ; 

                           echo " </span> </a> </li> ";
                        }
                    } else { 
                        echo " there is no record to show ";
                    }
                        ?> 
                    </ul>
               </div>
            </div>
        </div>

         <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                <i class="fas fa-users"></i> اخر  <?php echo $latestItems ?> منتجات
                </div>
                <div class="card-body">
                    <ul class="list-unstyled latest-users">
                        <?php 
                         if (!empty ( $theLatestItems)){
                            foreach ($theLatestItems as $item ){
                                echo "<li>". $item['Name'] .
                                " <a href='items.php?do=edit&itemid=" . $item['item_ID'] . " '> 
                                <span class='ed-btn float-left'> تعديل " ; 

                                

                            echo " </span> </a> </li> ";
                            }
                        }else { 
                            echo " there is no items to show ";
                        }
                        ?> 
                        </ul> 
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                <i class="fas fa-comments"></i> اخر <?php echo $latestCom  ?> تعليقات
                </div>
                <div class="card-body">
                    <?php 
                        $stmt = $con->prepare("SELECT 
                                       * FROM
                                        comments 
                                      
                                        ORDER BY 
                                        c_id DESC 
                                        LIMIT 
                                        $latestCom ");
                    $stmt->execute(); 
                    $comments = $stmt->fetchAll(); 
     
                    if(!empty($comments)){
                        foreach ( $comments as $comment) {
                            echo "<div class=comment-box>" ; 
                            echo "<span class='member-n'>" . $comment['vis'] . "</span>" ;
                            echo "<p class='member-c'>" . $comment['Comment'] . "</p>" ;
                            echo "</div>" ; 
                        }
                    } else {
                    echo "لايوجد تعليقات حتى الآن" ; 
                    }
                


                    ?>
                </div>
            </div>
        </div>




    </div>
</div>

   
   <?php 


    include $tpl.'footer.php';
} 

else {

    header('location:index.php') ; 
    exit() ; 
}

ob_end_flush(); 