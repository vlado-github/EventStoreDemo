<?php
	include 'Model/events.php';
	include 'Helpers/jsonserializer.php';
	include 'Helpers/connection.php';
	session_start();
	
	$client = ConnectionToEventStore::connect();

	if(isset($_GET["itemkey"])){
		if(isset($_SESSION['cartitems'])){
			$itemArray = $_SESSION['cartitems'];
			$itemArray[] = $_GET["itemkey"];
			$_SESSION['cartitems'] = $itemArray;		
		}else{
			$itemArray = array();
			$itemArray[] = $_GET["itemkey"];
			$_SESSION['cartitems'] = $itemArray;
		}		
	}else if(isset($_GET["removeitem"])){
		if(isset($_SESSION['cartitems'])){
			$itemArray = $_SESSION['cartitems'];
			unset($itemArray[$_GET["removeitem"]]);
			$itemArray = array_values($itemArray);
			$_SESSION['cartitems'] = null;
			$_SESSION['cartitems'] = $itemArray;
		}		
	}
	$itemArray = $_SESSION['cartitems'];
	
?>
<html>
<head>
		<link rel="stylesheet" type="text/css" href="style.css"/>
		<title>Cart</title>
</head>
<body>
	<?php include 'header.php'; ?>
	<div id="wrap">
	<h2>Cart</h2>
	<?php
		echo '<br/><table>';
		for($i=0; $i<count($itemArray); $i++){
			$json = $client -> get($itemArray[$i]);
			$item = JSONSerializer::serializeFromJSON($json);
			echo '<tr><td>'.$item->title.' '.$item->price.' '.$item->currency.'
				<a href="cart.php?removeitem='.$i.'">Remove</a></td></tr>';		
		}
		if(count($itemArray)>0){
			echo '<tr><td><a href="ordered.php?confirm=true">Confirm</a></td></tr>';
		}	
		echo '</table>';
	?>
	</div>
</body>

</html>