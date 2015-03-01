<?php

   namespace Cache\Store;


   class Memcache
   {

         public $cache;

         public function __construct( \Memcache $cache)
         {

              $this->cache = $cache;

         }

       public function __call($name, $params)
       {

           return call_user_func_array(array($this->cache,$name),$params);

       }

   }