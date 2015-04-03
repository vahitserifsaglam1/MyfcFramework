<?php 

  return [


      'Cache' => [ // cache ayarlarý
	      'driver'        => 'memcache',
		  'path'          => APP_PATH.'Stroge/Cache',
		  'defaultDriver' => 'predis'
	  ],
	  
	  'Session' => [ // session ayarlarý 
	  
	    'driver' => 'php',
		'path'   =>  APP_PATH.'Stroge/Session',
		'defaultDriver' => 'php'
		
	  ],

      'Cookie' => [ // cookie ayarlarý



      ],
      
      'predis' => [ // predis ayarlarý [ standart ayarlardýr, dokumanamanýz önerilir ]
           
          'cluster' => false,
           
          'default' => [
               
              'scheme'   => 'tcp',
              'host'     => '127.0.0.1',
              'port'     =>  6379,
              'database' =>  0,
               
          ]
           
      ]
	  
  ]
 

?>