<?php
return [
    
     'url' => 'localhost/MyfcFramework',
    
     'page' => [
     
          'charset' => 'utf-8',
         
          'language' => 'tr'
          
     ],
     

    
    'allIncludePath' => '',
    

    'alias' => [
        
        'Session' => 'Myfc\Session',
        'Cache' => 'Myfc\Cache',
        'Validate' => 'Myfc\Validate',
        'Mail'  => 'Myfc\Mail',
        'Carbon' => 'Carbon\Carbon',
        'Redirect' => 'Myfc\Redirect',
        'Event'  => 'Myfc\Event',
        'Route' => 'Myfc\Route',
        'Assets' => 'Myfc\Assets',
        'Crypt' => 'Myfc\Crypt',
        'Console' => 'Myfc\Console',
        'Upload' => 'Myfc\File\Upload',
        'IP'     => 'Myfc\Security\IP',
        'CSRF'   => 'Myfc\Security\Csrf'
     
    ],
    
    //start dosyaları
    
    'serviceProviders' => [

        'Myfc\Providers\Facade\Register',
        'Myfc\Providers\Url\Checker',
        'Myfc\Session\Starter',
        'Myfc\Providers\Error\Reporting',
        'Myfc\Providers\Language\Installer',
        'Myfc\Providers\Event\Installer',
        #'Myfc\Providers\Route\Runner'
        
        
        ]
    
    
    
];