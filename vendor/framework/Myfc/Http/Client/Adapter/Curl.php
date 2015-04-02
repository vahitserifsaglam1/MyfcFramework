<?php

   namespace Myfc\Http\Client\Adapter;

   use Myfc\Curl\Full;


  class Curl extends Full
  {

      public function __construct()
      { parent::__construct(false); }

      public function getName()
      {
          return "Curl";
      }

      public function boot()
      {

      }
  }