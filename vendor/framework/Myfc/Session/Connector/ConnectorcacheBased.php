<?php

   namespace Myfc\Session\Connector;


   class ConnectorcacheBased{

       public $cache;

       public function __construct( Cache $cache)
       {

           if( class_exists('Cache') )
           {
            $this->cache = $cache;
           }else{
              throw new \Exception('Cache sınıfınız bulunamadı');
               die();
           }

       }

     public function boot()
     {
         return new \Myfc\Session\Store\Cache( $this->cache );
     }

   }