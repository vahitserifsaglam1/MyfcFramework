<?php 

  return [


      'Cache' => [
	      'driver'        => 'memcache',
		  'path'          => APP_PATH.'Stroge/Cache',
		  'defaultDriver' => 'predis'
	  ],
	  
	  'Session' => [
	  
	    'driver' => 'php',
		'path'   =>  APP_PATH.'Stroge/Session',
		'defaultDriver' => 'php'
		
	  ],

      'Cookie' => [



      ]
	  
  ]
 

?>