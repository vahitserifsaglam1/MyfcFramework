<?php

 namespace Myfc;
 
 use Myfc\Singleton;
 
 use Myfc\Config;

  class Cache{

       public $configs;

       public $adapter;

       public $adapterList = array(

           'memcache' => true,
           'apc'      => true,
           'file'     => true,
           'predis'   => true,

       );

        public function __construct()
        {

           $configs = Config::get('strogeConfigs','cache');

            $this->adapter = Singleton::make('\Myfc\Adapter','Cache');

            $this->adapter->addAdapter(new Cache\CacheManager($this->configs,$this->adapterList));

        }

      public function __call( $name,$params )
      {

          return call_user_func_array(array($this->adapter->CacheManager->Cache,$name),$params);

      }


  }