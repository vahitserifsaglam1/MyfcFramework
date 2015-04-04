<?php
return [
    
     'url' => 'localhost/MyfcFramework',
    
     'page' => [
     
          'charset' => 'utf-8',
         
          'language' => 'tr'
          
     ],
     

    
    'allIncludePath' => '',
    

    'alias' => [
         
        'Route' => 'Myfc\Route',
        'Session' => 'Myfc\Session',
        'Cache' => 'Myfc\Cache',
        'Validate' => 'Myfc\Validate',
        'Mail'  => 'Myfc\Mail',
        'Carbon' => 'Carbon\Carbon',
        'Redirect' => 'Myfc\Redirect',
        'Event'  => 'Myfc\Event'
     
    ],
    
    //start dosyasý 
    
    'serviceProviders' => [
        
        'Myfc\Providers\Url\Checker',
        'Myfc\Session\Starter',
        'Myfc\Providers\Error\Reporting',
        'Myfc\Providers\Language\Installer',
        'Myfc\Providers\Event\Installer'
        
        
        ]
    
    
    
];