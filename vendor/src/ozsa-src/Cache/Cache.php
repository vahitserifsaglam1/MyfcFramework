<?php


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

           $configs = require APP_PATH.'Configs/strogeConfigs.php';

            $this->configs = $configs['cache'];

            $this->adapter = \Desing\Single::make('\Adapter\Adapter','Cache');

            $this->adapter->addAdapter(new Cache\CacheManager($this->configs,$this->adapterList));

        }

      public function __call( $name,$params )
      {

          return call_user_func_array(array($this->adapter->CacheManager->Cache,$name),$params);

      }


  }