<?php 

  return [

      'Cache' => [

          'driver' => 'predis',
          'memcache' => [

              'host' => '127.0.0.1',
              'port' => 11211

          ],
          
          'file' => [
              
              'path' => APP_PATH.'Stroge/Cache'
              
          ]

      ],
	  
	  'Session' => [ // session ayarları
	  
	    'driver' => 'php', // -> cacheBased,fileBased,php[önerilen]
		'path'   =>  APP_PATH.'Stroge/Session',
		'default' => 'php' // seçilen driver yüklü değilse
		
	  ],

      'Cookie' => [ // cookie ayarları



      ],
      
      'predis' => [ // predis ayarları [ standart ayarlardır, dokumanamanız önerilir ]

          'cluster' => false,

          'default' => [

              'scheme'   => 'tcp',
              'host'     => '127.0.0.1',
              'port'     =>  6379,
              'database' =>  0,

          ]

      ]
	  
  ];
