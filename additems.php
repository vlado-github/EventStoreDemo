<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css"/>
	<title>Add Product</title>
</head>
<body>
	<?php include 'header.php'; ?>
	<div id="wrap">
	<h2>Add Product</h2>
	<form id="add_item" method="post">
		<label>Title: </label><input type="text" name="txtTitle"/><br/>
		<label>Manufacturer: </label><input type="text" name="txtManufacturer"/><br/>
		<label>Price: </label><input type="text" name="txtPrice"/><br/>
		<label>Currency: </label>
			<select type="text" name="txtCurrency">
				<option value="USD">USD</option>		
				<option value="EUR">EUR</option>	
				<option value="CAD">CAD</option>		
			</select><br/>
		<label>Desc: </label><input type="text" name="txtDesc"/><br/>
		<label>Shipping: </label><input type="text" name="txtShipping"/><br/>
		<label>Image: </label><input type="text" name="txtImgUrl"/><br/>
		<button type="submit">Add</button>
	</form>
	</div>

</body>
</html>

<?php
	include 'Model/items.php';
	include 'Helpers/jsonserializer.php';
	include 'Helpers/connection.php';

	$client = ConnectionToEventStore::connect();
	if(isset($_POST["txtTitle"])){
		$title = $_POST["txtTitle"];
		$manufacturer = $_POST["txtManufacturer"];
		$price = $_POST["txtPrice"];
		$currency = $_POST["txtCurrency"];
		$desc = $_POST["txtDesc"];
		$shipping = $_POST["txtShipping"];
		$image = $_POST["txtImgUrl"];
	
		$item = new Item;
		$item -> itemID = uniqid();
		$item -> title = $title;
		$item -> manufacturer = $manufacturer;
		$item -> price = $price;
		$item -> currency = $currency;
		$item -> desc = $desc;
		$item -> shipping = $shipping;
		$item -> image = $image;
		
		$key = "item:".$item->itemID.":".time();
		$value = JSONSerializer::serializeToJSON($item);
		$client->set($key,$value);
		echo $client->get($key);
	}
	//$result = $client->keys("item*");
	//for($i=0; $i<count($result);$i++){
	//	echo $client->get($result[$i]).'<br/>';
	//}
	
?>

