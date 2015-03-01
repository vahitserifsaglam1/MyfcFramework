<?php

  namespace Session\Connector;


  class Connectorphp
  {


      public function __construct()
      {

          if( isset ( $_SESSION ) )
          {

              return true;

          }else{

              throw new \Exception("Sunucunuzda sessionlar başlatılmamış ");
              return false;

          }

      }
      public function boot()
      {

          return new \Session\Store\Php();

      }

  }