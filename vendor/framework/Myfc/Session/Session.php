<?php

 namespace Myfc;
 
 use Myfc\Singleton;

  class Session{

         public $configs;

         public $manager;


         public function __construct()
         {

             $this->configs = Config::get('strogeConfigs');

             $this->manager = Singleton::make('Myfc\Session\SessionManager',$this->configs);

             $this->manager->boot();

         }

      public static function boot()
      {

          return new static();

      }

      public function __call($name, $params)
      {

         return call_user_func_array(array($this->manager->connector,$name),$params);

      }



  }