<?php

  namespace Cache;


   class CacheManager
   {

       public $configs;

       public $driver;

       public $defaultDriver = 'file';

       public $driverList;

       public $connector;

       public $Cache;

       public function __construct( Array $configs, Array $driverList )
       {

           $this->configs = $configs;

           $this->defaultDriver = $configs['defaultDriver'];

           $this->driver = (isset($this->configs['driver'])) ? $this->configs['driver']: $this->defaultDriver;

           $this->driverList = $driverList;

           $this->connector = \Desing\Single::make('\Cache\Connector\Connector'.$this->driver,$this->configs);

           $this->Cache = $this->connector->boot();


       }



       public function checkDriver( $driver )
       {

           return ( isset ( $this->driverList[$driver ] ) ) ? true:false;

       }

       public function getName()
       {
           return "CacheManager";
       }


   }