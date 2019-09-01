<?php 

session_start(); 
include "init.php";





?> 
<h1 class="text-center">  السلة  </h1>

<div class=" container">
<?php 

if(!empty($_SESSION['cart'])){
$items = $_SESSION['cart'] ; 

$wherein =implode("','", $items);

// echo $wherein;

$stmt = $con->prepare("SELECT * FROM items WHERE  item_ID  IN ('.$wherein.'); ");

$stmt->execute(array());



$items = $stmt->fetchAll(); 

  


// if(!empty($items)){ 

?>
   
    <div class="table-responsive">
       <form action="" method="POST"> 

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

          <input type="submit" name="update" value="update"> 

         
       </div>
    </div>
    <?php
    echo "</td>" ;

    echo "<td>" . $item['Price'] ."</td>" ; 
    echo "<td> 

    
    <a href='cart.php?do=delete&itemid= "  . $item['item_ID'] . " 'class='del-btn confirm '> حذف</a> "; 



    echo "</td>"; 

    echo "</tr>"; 
    }

    ?>

    <!-- for confirm the delet btn  -->
    <script>   $('.confirm').click(function(){ return confirm('هل انت متأكد  ?'); }); </script> 

    </table>


    </form>

       <div class="col-md-6">
          <a href="index.php"><button class="btn btn-dark"> متابعة التسوق  </button></a>
          <a href="checkout.php"><button class="btn btn-danger "> انهاء التسوق </button></a> 
      </div>
 
    <?php 

    if(isset($_POST['update'])){

       $qty = $_POST['qty']  ; 

      echo $qty ; 
    }


?>


    </div>

    <?php  } else { echo "<p style='padding-top:100px; padding-bottom:100px' ; > لايوجد منتجآت قم بأضافة منتج </p> " ; } ?>

    </div>

 <?php 
include $tpl . 'footer.php'; 

 ?>

