<?php
	include 'Model/events.php';
	include 'Helpers/jsonserializer.php';
	include 'Helpers/connection.php';
	session_start();
	
	$client = ConnectionToEventStore::connect();
	
	if(isset($_GET["confirm"])) {
		if(isset($_SESSION['cartitems'])){
			$itemArray = $_SESSION['cartitems'];
			$_SESSION['ordereditems'] = array();
			for($i=0; $i<count($itemArray); $i++){
				array_push($_SESSION['ordereditems'],$itemArray[$i]);
			}
			$_SESSION['cartitems'] = null;
		}
	}else if(isset($_GET["cancelitem"])){
		if(isset($_SESSION['ordereditems'])){
			$itemArray = $_SESSION['ordereditems'];
			unset($itemArray[$_GET["cancelitem"]]);
			$itemArray = array_values($itemArray);
			$_SESSION['ordereditems'] = null;
			$_SESSION['ordereditems'] = $itemArray;
		}		
	}
	$itemArray = $_SESSION['ordereditems'];
	
?>
<html>
<head>
		<link rel="stylesheet" type="text/css" href="style.css"/>
		<title>Ordered Items</title>
</head>
<body>
	<?php include 'header.php'; ?>
	<div id="wrap">
	<h2>Ordered</h2>
	<?php
		echo '<br/><table>';
		for($i=0; $i<count($itemArray); $i++){
			$json = $client -> get($itemArray[$i]);
			$item = JSONSerializer::serializeFromJSON($json);
			echo '<tr><td>'.$item->title.' '.$item->price.' '.$item->currency.'
				<a href="ordered.php?cancelitem='.$i.'">Cancel order</a></td></tr>';		
		}	
		echo '</table>';
	?>
	</div>
</body>

</html>