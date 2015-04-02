<?php

  namespace Myfc\Cache\Connector;


  use Myfc\Predis\Installer;
  class Connectorpredis
  {

      public $redis;

      public function __construct()
      {

          $predis = Installer::create();

           $this->redis = $predis;

      }


      public function getName()
      {

          return "predis";

      }

      public function boot()
      {

          return new \Myfc\Cache\Store\Predis($this->redis);

      }
  }