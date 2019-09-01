<?php


session_start() ; 
$pageTitle = 'السلة' ;
include "init.php";



if (isset ($_GET['do'])) { // ex: www.page.php?do=tat
$do = $_GET['do']; 

} else { 
$do = 'manage'; // main page
}


if ($do == 'manage') {

$ip = get_ip(); 


            // make link between the table  by the JOIN 
            $stmt = $con->prepare("SELECT 

            cart.* , items.*
            FROM 
            cart 
            INNER JOIN
         items
            ON 
         items.item_ID = cart.items_ID
         WHERE 
         ip_add = '$ip'  
            ");

            $stmt->execute(); 

            // assign to variable 

            $items = $stmt->fetchAll(); 

            if(!empty($items)){ 
            ?> 
            <h1 class="text-center">  السلة  </h1>

            <div class=" container">
            <div class="table-responsive">
               <form action="?do=update" method="POST"> 

            <table class="main-table text-center table table-bordered">
            <tr>
            <!-- <td>الصورة</td> -->
            <td> img </td>
            <td> id </td>

            <td> الاسم </td>
            <td> السعر </td>
            <td> الكمية </td>
            <td> المجموع </td>
            <td> التحكم </td> 
            </tr>














            <?php

            foreach($items as $item) {
            echo "<tr>" ; 
            echo "<td>";
            echo'<img src="uploads/avatars/'.$item['Image'].' " style="width:50px ; height:50px;"> ';
            echo "</td>" ; 
            echo "<td>" . $item['item_ID']. "</td>" ;

            echo "<td>" . $item['Name']. "</td>" ;
            echo "<td>" . $item['Price']. "</td>" ; 

                  echo "<td>" ; ?>  

            <div class="center">
               <div class="input-group">
                    


                  <input type="number"  name="qty" value = "1"  class="form-group input-number" min="1" max="5" style="
                  width: 100%">
                  <!-- <input type="hidden" name="itemid"  value="<?php echo $item['items_ID'] ?>" />  -->
                  <!-- <input type="submit" name="update" value=" تحديث "  /> -->

                  <a href="?do=update&itemid=<?php echo $item['itemsid'] ?>"> update </a>




                 
               </div>
            </div>
            <?php
            echo "</td>" ;
       
            echo "<td>" . $item['Price'] * $item['Quantity']. "</td>" ; 
            echo "<td> 

            
            <a href='cart.php?do=delete&itemid= "  . $item['item_ID'] . " 'class='del-btn confirm '> حذف</a> "; 



            echo "</td>"; 

            echo "</tr>"; 
            }

            ?>

            <!-- for confirm the delet btn  -->
            <script>   $('.confirm').click(function(){ return confirm('هل انت متأكد  ?'); }); </script> 

            </table>

            <button><a href="checkout.php"></a> انهاء التسوق </button>



















            </form>
            <?php 


?>







            </div>

            <?php } else { echo " لايوجد منتجآت قم بأضافة منتج  " ; } ?>

            </div>

<!-- ================ insert to cart table ================ -->

<?php } elseif ($do == 'add')  {


$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? 
intval($_GET['itemid']) : 0 ; 
// select all data depend on this ID 

$ip = get_ip() ; 

$stmt = $con->prepare("INSERT INTO cart(items_ID , ip_add ) VALUES(:zid , :zip ) "); 
$stmt->execute(array(
   'zid' => $itemid, 
   'zip' => $ip  
)); 


$count = $stmt->rowCount(); // variable 
if ($count > 0 ){   

   echo "yes";

} else {
   echo "no"; 
}


}elseif ($do == 'delete'){



   $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? 
   intval($_GET['itemid']) : 0 ; 

   // select all data depend on this ID 

  $check = checkItem('item_ID' , 'items', $itemid) ;

$ip = get_ip() ; 

   if ($check > 0 ){   

       $stmt = $con->prepare("DELETE FROM cart WHERE items_ID = '$itemid' AND ip_add='$ip'"); 
       //bindParam = link with parameter 

       $stmt->execute(); 

       $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() ."تم حذف المنتج  "; 
       redirectHome($theMsg) ; 

   } else {

      $theMsg =  "<div class='alert alert-danger'> this id dosent exist </div>" ; 
       redirectHome($theMsg ) ; 

   }

echo '</div>';


}elseif ($do = 'update'){

   if ($_SERVER['REQUEST_METHOD'] == "POST"){

   $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? 
   intval($_GET['itemid']) : 0 ; 


      $qty = $_POST['qty'] ; 
      
      // foreach ($qty as $value ){
         // foreach ($itemid as $id) {
      
      $stmt = $con->prepare(" UPDATE cart SET Quantity = ? WHERE items_ID= ? ");
      $stmt->execute(array($qty , $itemid)); 
       
      
      // }
      // }
      if($stmt->rowCount() > 1 ){
      echo 'good' ; 
      } else 
      {echo $qty[1]; }
      
      
      }


}





include $tpl.'footer.php';
?>