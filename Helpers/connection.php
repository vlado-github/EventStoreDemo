<?php

// WARNING: right now, support for redis-cluster is experimental,
// unoptimized and in its very early stages of development. To
// play with it you must have a correct setup of redis-cluster
// running (oh hai mr. obvious) and Predis v0.7-dev fetched from
// the redis_cluster branch of the Git repository.
	require 'predis/autoload.php';
	
	class ConnectionToEventStore{
	
		private static $servers = array(
	    'tcp://127.0.0.1:6380',
	    'tcp://127.0.0.1:6381',
	    'tcp://127.0.0.1:6383',);
	    
	    public static function connect(){
			try {
			 
				// Developers can specify which kind of cluster strategy the
				// client should use with the recently added 'cluster' option
				// and the following values:
				//   - predis : good old client-side sharding (default)
				//   - redis  : redis-cluster
				 
				$client = new Predis\Client($servers, array('cluster' => 'redis'));
				
				return $client;
			}catch (Exception $e) {
			    echo "Couldn't connected to Redis";
			    echo $e->getMessage();
			}
		}
	}

?>