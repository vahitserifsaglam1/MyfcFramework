<?php
return [
    
    /*
     * 
     *  ********************** Standart URL Tanımını yapar 
     * 
     *  Düzenlenmesi gerek 
     * 
     */
     'url' => 'localhost/MyfcFramework',
    
    /* Default timezone atamasını İstanbula yapar */
    
     'timezone' => 'Europe/Istanbul',
 
     /*
      * 
      *   MyfcFrameworkde view dosyalarınızı hazırlarken kullanacağınız template engine
      *   2 engine desteklenir => smarty
      *                           twig
      * 
      *   Eğer engine kullanmak istemiyorsanız 'php' girmeniz gerekir
      * 
      */
  
    'templateEngine' => 'twig',
    
    /*
     * Framework içinde kullanılacak facede sınıflarını hazırlar
     *  
     *  Örnek facade : Myfc\Facade\Session,
     *                 Myfc\Facade\Cookie
     *   
     */
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
        'CSRF'   => 'Myfc\Security\Csrf',
        'View'   => 'Myfc\View',
        'App' => 'Myfc\App',
        'Request' => 'Myfc\Http\Request',
        'Response' => 'Myfc\Http\Response'],
     
    /*
     * 
     *  MyfcFrameworkun başlatılırken gerekli ayarlamalarını yapar
     *  
     *  Bootstrap sınıfı içinde runServiceProviders() fonksiyonunda oluşturulurlar
     * 
     */
    
    'serviceProviders' => [

        'Myfc\Providers\Facade\Register',
        'Myfc\Providers\Url\Checker',
        'Myfc\Session\Starter',
        'Myfc\Providers\Error\Reporting',
        'Myfc\Providers\Language\Installer',
        'Myfc\Providers\Event\Installer',
        #'Myfc\Providers\Route\Runner',
        'Myfc\Providers\Config' ] ];