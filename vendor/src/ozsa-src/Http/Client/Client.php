<?php

 namespace Http;


 class Client
 {

   protected $adapter;

     public function __construct()
     {

      $this->adapter = $adapter = \Desing\Single::make('\Adapter\Adapter','Client');

     }

     public function startSocket( $host, $port )
     {

     }



 }