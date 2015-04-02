<?php

 namespace Myfc\Http;

 use Myfc\Http\Client\Adapter\Socket;
 use Myfc\Http\Client\Adapter\Curl;
 use Myfc\App\Singleton;

 class Client
 {

   protected $adapter;

     public function __construct()
     {

      $this->adapter = $adapter = Singleton::make('\Myfc\Adapter','Client');

     }

     public function startSocket( $host, $port )
     {

          $this->adapter->addAdapter( new Socket($host,$port) );

     }

     public function startCurl()
     {

          $this->adapter->addAdapter( new Curl() );

     }

   public function get($name)
   {
        return $this->adapter->$name;
   }


 }