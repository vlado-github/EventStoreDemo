<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css"/>
	<title>Welcome</title>
</head>
<body>
<?php include 'header.php'; ?>
<div id="wrap">
	<div id="products">	
	<?php
		include 'Model/events.php';
		include 'Helpers/jsonserializer.php';
		include 'Helpers/connection.php';
	
		$client = ConnectionToEventStore::connect();
	
		$result = $client->keys("item*");
		for($i=0; $i<count($result);$i++){
			$item = JSONSerializer::serializeFromJSON($client->get($result[$i]));
			$img = $item->image;
			$title = $item->title;
			echo '<div class="img">
					  <a href="details.php?itemkey='.$result[$i].'">
					  <img src="'.$img.'" 
					  		alt="'.$title.'" width="167" height="200">
					  </a>
					  <div class="desc">'.$title.'</div>
					</div><br/>';
		}
	?>	
	</div>
</div>
</body>

</html>