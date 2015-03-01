<?php

  namespace Cache\Connector;


  class Connectorpredis
  {

      public $redis;

      public function __construct()
      {

          $predis = \Predis\Installer::create();

           $this->redis = $predis;

      }


      public function getName()
      {

          return "predis";

      }

      public function boot()
      {

          return new \Cache\Store\Predis($this->redis);

      }
  }