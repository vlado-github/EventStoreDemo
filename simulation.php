<?php
	include 'Model/events.php';
	include 'Helpers/jsonserializer.php';
	include 'Helpers/connection.php';

	$client = ConnectionToEventStore::connect();
	
	$result = $client->keys("item*");
	//$client -> flushall();
	
	if(isset($_POST["numOfUsers"])){
		$numOfUsers = $_POST["numOfUsers"];
		$numOfEvents = $_POST["numOfEvents"];
		$count = 0;
		for($i=0; $i<$numOfUsers; $i++){
			for($j=0; $j<$numOfEvents; $j++){
				$count++;
				$current_time = time();
				$eventItemAdded = new ItemEvent;
			 	$eventItemAdded -> itemID = $result[rand(0,count($result))-1];
			 	$eventItemAdded -> customerID = 'user'.$i;
			 	$eventItemAdded -> timestamp = date('Y-m-d H:i:s',$current_time);
			 	if($count % 2 === 0){
			 		$eventItemAdded -> eventName = EventNames::EventItemPreviewed;
			 	}else if($count % 3 === 0){
					$eventItemAdded -> eventName = EventNames::EventItemAddedToCart;		 	
			 	}else if($count % 5 === 0){
			 		$eventItemAdded -> eventName = EventNames::EventItemRemovedFromCart;
			 	}else if($count % 7 === 0){
			 		$eventItemAdded -> eventName = EventNames::EventItemOrdered;
			 	}else if($count % 11 === 0){
			 		$eventItemAdded -> eventName = EventNames::EventItemCanceled;
			 	}else{
			 		$eventItemAdded -> eventName = EventNames::EventItemPreviewed;
			 	}
			 	$key = "event:".$eventItemAdded->customerID.":".$current_time.":".$eventItemAdded->eventName;
			 	$value = JSONSerializer::serializeToJSON($eventItemAdded); 
				$client->set($key, $value);			
			}	
		}
	}
	//$events = $client->keys("event*");
	//for($i=0; $i<count($events);$i++){
	//	echo $events[$i].'<br/>';
	//}	
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css"/>
	<title>Simulation</title>
</head>
<body>
	<?php include 'header.php'; ?>
	<div id="wrap">
	<h2>Simulation</h2>
	<form id="simulation_config" method="post">
		Simulate eCommerce for
		<select id="num_of_users" name="numOfUsers">
			<option value="100">100</option>	
			<option value="200">200</option>	
			<option value="300">300</option>	
			<option value="400">400</option>	
			<option value="500">500</option>	
			<option value="600">600</option>	
			<option value="700">700</option>	
			<option value="800">800</option>	
			<option value="900">900</option>	
			<option value="1000">1000</option>	
		</select> users and 
		<select id="num_of_events" name="numOfEvents">
			<option value="100">100</option>	
			<option value="200">200</option>	
			<option value="300">300</option>	
			<option value="400">400</option>	
			<option value="500">500</option>	
			<option value="600">600</option>	
			<option value="700">700</option>	
			<option value="800">800</option>	
			<option value="900">900</option>	
			<option value="1000">1000</option>	
		</select> events per user.
		<button type="submit">GO!</button>
	</form>
	<label id="status">
		<?php echo $count." inserted";  ?>	
	</label><br/>
	</div>
</body>
</html>




