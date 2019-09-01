<?php 

session_start();

if(empty($_SESSION['cart'])){
    $_SESSION['cart'] = array(); 

}

array_push($_SESSION['cart'] , $_GET['itemid']);

// ?>

<p> product was seccessfuly added </p>
<a href='shopping-cart.php'> view cart </a> 

<?php 
// session_start();
// if(isset($_GET['itemid'])):
// 	if(!isset($_SESSION['cart'])):
// 		$_SESSION['cart'] = array();
// 	endif;	
	
// 	$itemID = (isset($_GET['itemid']) && !empty($_GET['itemmid']) ? (int) $_GET['itemid'] : null);
		
// 	if($itemID !== null):
// 		$qty = 1;
// 		$_SESSION['cart'][$itemID] = $qty;
// 	endif;
	
// 	//echo "<pre>";
// 	//print_r($_SESSION['cart']);
// 	//echo "</pre>";
// endif;

?>




