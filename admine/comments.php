<?php
/*
=== manage comment page 
== you can  edite || delet comments from here 

*/

session_start() ; 

if (isset($_SESSION['username'])) {

    $pageTitle = 'التعليقات' ; // for title 

    include 'init.php'; 

     $do =isset($_GET['do'])? $_GET['do'] : 'manage' ;  // اختصار دالة if

    

     if ($do == 'manage') { //manage member page
     

       // SElect all comment except admin 
     $stmt = $con->prepare("SELECT 
                                comments.* , items.Name 
                                FROM 
                                comments 

                                INNER JOIN 
                                items 
                                ON 
                                items.item_ID = comments.item_ID 

                            
                                ORDER BY 
                                c_ID DESC 


     
     
     
      ");
     $stmt->execute(); 

     // assign to variable 

     $rows = $stmt->fetchAll(); 
     
     if (!empty ($rows)) {
     ?> 
        <h1 class="text-center">  ادآرة التعليقات  </h1>

        <div class=" container">
        <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td> التعليق  </td>
                        <td> اسم المنتج </td>
                        <td> اسم العميل </td> 
                        <td> تاريخ الاضافه </td>
                        <td> التحكم </td> 
                    </tr>

                <?php

                foreach($rows as $row) {
                    echo "<tr>" ; 
                     echo "<td>" . $row['c_ID'] . "</td>" ; 
                     echo "<td>" . $row['Comment']. "</td>" ;
                     echo "<td>" . $row['Name']. "</td>" ; 
                     echo "<td>" . $row['vis']. "</td>" ; 
                     echo "<td>".$row['comment_Date'] ."</td>" ; 
                     echo "<td> 
 
                     <a href='comments.php?do=edit&comid= "  . $row['c_ID'] . " '  class='ed-btn'> تعديل </a>
                     <a href='comments.php?do=delete&comid= "  . $row['c_ID'] . " 'class='del-btn'> حذف </a> "; 
                    

                     //if regstatus = 0 show the button .. we can use it any where 
                     if ($row['Status'] == 0){
                        echo  "<a href='comments.php?do=activate&comid=" . $row['c_ID'] . " '  class='app-btn'> تفعيل </a>";
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


     <?php  } else { echo " <div class='container' style='margin-top:200px;'> لايوجد تعليقات ..  </div>   "; }

    }  elseif ( $do == 'edit') { // EDIT page  
        
        // for secure for the user id 

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? 
        intval($_GET['comid']) : 0 ; 
        // select all data depend on this ID 

        $stmt = $con->prepare("SELECT * FROM comments WHERE c_ID= ? "); 
        $stmt->execute(array($comid)); 
        $row = $stmt->fetch(); // array of info from database for this user 
        
        $count = $stmt->rowCount(); // variable 


        if ($count > 0 ){   ?> 

        <!-- // if you have the same userID ,, show the form  -->

            <h1 class="text-center">  تعديل التعليق </h1>
            <div class=" container">
            <form class="form-horizontal" action="?do=update" method="POST">
                <input type="hidden" name="comid"  value="<?php echo $comid ?>" /> 
            <!-- comment field  -->
            <div class="form=group">
            <label class="col-sm-2 control-label"> التعليق </label>
                <div class="col-sm-10 col-md-5">
                    <textarea name="comment"> <?php echo $row['Comment'] ;  ?> </textarea> 
            </div>
            </div>
           
        <br>
            <!-- submit  -->
            <div class="form=group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" value="حفظ" class="btn btn-primary btn-lg" />
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

    echo "<h1 class='text-center'>  تحديث التعليق  </h1>" ; 
    echo "<div class='container'>";

if ($_SERVER['REQUEST_METHOD'] == "POST"){

    // get variable from the form 

    $comid = $_POST['comid']; 
    $comment= $_POST['comment']; 
   

//form validate 



// update the data base with this info  
$stmt = $con->prepare("UPDATE comments SET Comment=? WHERE c_ID = ? ") ; 
$stmt->execute(array($comment,$comid ));

$theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() .'تم التحديث ' ; 
redirectHome($theMsg ) ;
 }


 else {

  $theMsg =  "<div class='alert alert-danger'> sorry you cant browser update page </div> "; 

   redirectHome($theMsg) ; 


}

echo "</div>" ; 


  } elseif ($do == 'delete'){ // delet page 

    echo "<h1 class='text-center'>  حذف التعليقات  </h1>" ; 
    echo "<div class='container'>";


        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? 
        intval($_GET['comid']) : 0 ; 

        // select all data depend on this ID 

       $check = checkItem('c_ID' , 'comments', $comid) ;



        if ($check > 0 ){   

            $stmt = $con->prepare("DELETE FROM comments WHERE c_ID = :zid"); 
            //bindParam = link with parameter 
            $stmt->bindParam(':zid' , $comid); 

            $stmt->execute(); 

            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() ." تم الحذف "; 
            redirectHome($theMsg) ; 



        } else {

           $theMsg =  "<div class='alert alert-danger'> this id dosent exist </div>" ; 
            redirectHome($theMsg ) ; 

        }

echo '</div>';


}elseif($do == 'activate') { // activate page 

    echo "<h1 class='text-center'> تفعيل التعليق </h1>" ; 
    echo "<div class='container'>";


        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? 
        intval($_GET['comid']) : 0 ; 

        // select all data depend on this ID 

       $check = checkItem('c_ID' , 'comments', $comid) ;



        if ($check > 0 ){   

            $stmt = $con->prepare("UPDATE comments SET Status = 1 WHERE c_ID =? "); 
           
            $stmt->execute(array("$comid")); 

            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() ."تم التفعيل  "; 
            redirectHome($theMsg , 8 ) ; 

        } else {

           $theMsg =  "<div class='alert alert-danger'> this id dosent exist </div>" ; 
            redirectHome($theMsg , 8 ) ; 

        }

echo '</div>';

}


    include $tpl.'footer.php';

} else {

    header('location:index.php') ; 
}