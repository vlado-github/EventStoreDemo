<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css"/>
	<title>Dashboard</title>
</head>
<body>
	<?php include 'header.php'; ?>
	<div id="wrap">
		<h2>Dashboard</h2>
		<div id="events">	
		<?php
			include 'Model/items.php';
			include 'Helpers/jsonserializer.php';
			include 'Helpers/connection.php';
			include 'reportfactory.php';
			
			$client = ConnectionToEventStore::connect();
			
			$items = $client->keys("item*");
			$labelSet = array();
			$dataSetPreviewed = array();
			for($i=0; $i<count($items); $i++){
				$resultPreviewed = $client->keys("event*".$items[$i]);
				$resultAdded = $client->keys("event*ItemAddedToCart:".$items[$i]);
				$resultRemoved = $client->keys("event*ItemRemovedFromCart:".$items[$i]);
				$resultOrdered = $client->keys("event*ItemOrdered:".$items[$i]);
				$resultCanceled = $client->keys("event*ItemCanceled:".$items[$i]);
				$product = JSONSerializer::serializeFromJSON($client->get($items[$i]));
				$labelSet[] = $product->title;
				$dataSetPreviewed[] = count($resultPreviewed);
				$dataSetAdded[] = count($resultAdded);
				$dataSetRemoved[] = count($resultRemoved);
				$dataSetOrdered[] = count($resultOrdered);
				$dataSetCanceled[] = count($resultCanceled);
			}
			ReportFactory::Create($dataSetPreviewed,$labelSet, "images/previewedreport.png", "How many items have been previewed.", "Items previewed"); 
			ReportFactory::Create($dataSetAdded,$labelSet,"images/addedtocartreport.png", "How many items have been added to cart.", "Items added to cart");
			ReportFactory::Create($dataSetRemoved,$labelSet,"images/removedfromcartreport.png", "How many items have been removed from cart.", "Items removed from cart");
			ReportFactory::Create($dataSetOrdered,$labelSet,"images/orderedreport.png", "How many items have been ordered.", "Items ordered");
			ReportFactory::Create($dataSetCanceled,$labelSet,"images/canceledreport.png", "How many items have been canceled.", "Items canceled");
	
			 echo '<img src="images/previewedreport.png"/><br/>';	
			 echo '<img src="images/addedtocartreport.png"/><br/>';
			 echo '<img src="images/removedfromcartreport.png"/><br/>';
			 echo '<img src="images/orderedreport.png"/><br/>';
			 echo '<img src="images/canceledreport.png"/><br/>';
		?>
		</div>
	</div>
</body>
</html>