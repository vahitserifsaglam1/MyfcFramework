<?php

  namespace Myfc\Cache\Connector;


  class Connectorapc
  {


       public function __construct()
       {

           if (function_exists('apc_fetch')) {

               return true;


           } else {

               throw new \Myfc\Cache\Exceptions\extensionsExceptions\extensionNotLoadedException(sprint_f("%s eklentisi sunucunuzda kurulu değil",$this->getName()));

               return false;

           }
       }

           public function getName()
           {

               return 'apc';

           }

      public function boot()
      {

          return new \Myfc\Cache\Store\Apc();

      }




  }