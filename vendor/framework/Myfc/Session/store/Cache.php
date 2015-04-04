<?php


  namespace Myfc\Session\Store;



  class Cache{

      public $cache;

      public function __construct($cache)
      {

          $this->cache = $cache;

      }

      public function __call($name,$params )
      {

          return call_user_func_array(array($this->cache,$name),$params);

      }

  }