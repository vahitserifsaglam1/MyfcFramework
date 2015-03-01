<?php

  class Session{

         public $configs;

         public $manager;


         public function __construct()
         {

             $this->configs = require APP_PATH.'Configs/strogeConfigs.php';

             $this->manager = \Desing\Single::make('\Session\SessionManager',$this->configs);

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

      public static function __callStatic($name,$params)
      {
          $s = new static;
          return call_user_func_array(array($s->manager->connector,$name),$params);
      }

  }