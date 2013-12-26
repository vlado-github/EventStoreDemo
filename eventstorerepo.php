<?php
	include 'Model/items.php';
	include 'Model/events.php';
	include 'Helpers/jsonserializer.php';
	include 'Helpers/connection.php';

	class EventStoreRepo{
		public static function saveEvent($key, $timestamp, $userid, $eventName){
			$itemEvent = new ItemEvent;
			$itemEvent -> itemID = $key;
			$itemEvent -> customerID = $userid;
			$itemEvent -> timestamp = $timestamp;
			$itemEvent -> eventName = $eventName;
			$json = JSONSerializer::serializeToJSON($itemEvent);
			$key = "event:".$userid.":".$timestamp.":".$eventName.":".$key;
			$client = ConnectionToEventStore::connect();
			$client -> set($key,$json);		
		}	
	}
?>