<?php

  namespace Cache\Connector;


  class Connectormemcache

  {

       public $cache;

      public function __construct()
      {

           if ( extension_loaded('memcache') )
           {

               $cache = new \Memcache();

               $cache->connect('127.0.0.1', 11211);

               $this->cache = $cache;


           }else{

            throw new \Cache\Exceptions\extensionsExceptions\extensionNotLoadedException(' Sunucuda %s eklentisi bulunamadı',$this->getName());

           }

      }

      public function boot()
      {
          return new \Cache\Store\Memcache( $this->cache );
      }

      public function getName()
      {
          return 'memcache';
      }

  }