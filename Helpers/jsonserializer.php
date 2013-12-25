<?php
	class JSONSerializer{
		public static function serializeToJSON($_obj){
			return json_encode($_obj);	
		}
		
		public static function serializeFromJSON($_json){
			return json_decode($_json);		
		}
	}

?>