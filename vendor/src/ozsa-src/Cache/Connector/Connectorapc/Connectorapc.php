<?php

  namespace Cache\Connector;


  class Connectorapc
  {


       public function __construct()
       {

           if (function_exists('apc_fetch')) {

               return true;


           } else {

               throw new \Cache\Exceptions\extensionsExceptions\extensionNotLoadedException(sprint_f("%s eklentisi sunucunuzda kurulu değil",$this->getName()));

               return false;

           }
       }

           public function getName()
           {

               return 'apc';

           }

      public function boot()
      {

          return new \Cache\Store\Apc();

      }




  }