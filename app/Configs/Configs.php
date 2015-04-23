<?php
return [
    
    /*
     * 
     *  ********************** Standart URL Tanımını yapar 
     * 
     *  Düzenlenmesi gerek 
     * 
     */
     'url' => 'http://localhost/dokumantasyon',
    
    /* Default timezone atamasını İstanbula yapar */
    
     'timezone' => 'Europe/Istanbul',
 
     /*
      * 
      *   MyfcFrameworkde view dosyalarınızı hazırlarken kullanacağınız template engine
      *   2 engine desteklenir => smarty
      *                           twig
      *                           MyfcTemplate // yapım aşamasında
      * 
      *   Eğer engine kullanmak istemiyorsanız 'php' girmeniz gerekir
      * 
      */
  
    'templateEngine' => 'twig',
    
    'MyfcTemplate' => [
        
        'templatePath' => VIEW_PATH,
        'fileExtension' => '.myfc.php'
    ],
    
     /**
     * Twig template ayarları
     *
     * -> sadece  twig seçili olduğunda kullanılır
     */
    'twig'    =>  [
             'debug' => false,
             'charset' => 'utf-8',
             'cache' => false, // Store cached files under cache directory
             'strict_variables' => true,],
    
    /*
     * 
     *  Smarty ayarları 
     * 
     *   -> sadece smarty seçili olduğunda kullanılır
     *  
     */
    'smarty' => [
        
        'templateDir' => VIEW_PATH,
        'compileDir'  => VIEW_PATH.'smarty/compile/',
        'configDir'   => APP_PATH.'Configs/',
        'cacheDir'    => APP_PATH.'Stroge/Cache/',
        'cacheTime'   => Smarty::CACHING_LIFETIME_CURRENT
         
    ],
    
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
        'Response' => 'Myfc\Http\Response',
        'File' => 'Myfc\File'],
     
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
        'Myfc\Providers\Config',
        'Myfc\Providers\App\Installer' ] ];