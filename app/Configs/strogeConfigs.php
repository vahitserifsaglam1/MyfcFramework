<?php 

  return [


      'Cache' => [ // cache ayarlarý
	      'driver'        => 'memcache', // -> apc,memcache[önerilen],file,predis
		  'path'          => APP_PATH.'Stroge/Cache',
		  'defaultDriver' => 'predis' // seçilen driver yüklü deðilse 
	  ],
	  
	  'Session' => [ // session ayarlarý 
	  
	    'driver' => 'php', // -> cacheBased,fileBased,php[önerilen]
		'path'   =>  APP_PATH.'Stroge/Session',
		'defaultDriver' => 'php' // seçilen driver yüklü deðilse 
		
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