<?php

  namespace Myfc\Session;

   use Myfc\Singleton;

  class SessionManager
  {

      public $configs;

      public $driver;

      public $connector;

      public $driverList = [

          'php'        => true,
          'cacheBased' => true,
          'fileBased'  => true

      ];

      public function __construct( Array $configs )
      {

          $configs = $configs['Session'];

          $this->configs = $configs;

          $this->driver = ($this->checkDriver($configs['driver'])) ? $this->configs['driver']:$this->configs['defaultDriver'] ;

          $this->boot();

      }

      private function checkDriver( $driver )
      {

          if( isset($this->driverList[$driver] ) )
          {

              return true;

          }
          else{

              return false;
          }

      }

      public function boot()
      {

           $this->connector = Singleton::make('Myfc\Session\Connector\Connector'.$this->driver,$this->configs);

           $this->connector = $this->connector->boot();

      }


  }