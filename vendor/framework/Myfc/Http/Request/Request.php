<?php

 namespace Myfc\Http;
 
  use Myfc\Singleton;
  use Myfc\Helpers\Http;
 
  class Request
  {

      use Http;
      /** they are http versions */
      const VERSION_11 = "HTTP/1.1";
      const VERSION_10 = "HTTP/1.0";
      /**
       * @var
       *
       *  Instance of __CLASS__
       */
      private static $_singleton;
      protected $client;
      protected $responseGet;
      protected $responsePost;

      protected $server;

      /**
       *
       *  Class Starter method
       *
       */
      public function __construct()
      {
           $this->client = Singleton::make('\GuzzleHttp\Client');
          

      }
      
      public function getName()
      {
          
          return "request";
          
      }

      /**
       * @return Request
       *
       *  Return the __CLASS__ instance
       */
      public static function this()
      {
          if (self::$_singleton === null) {
              self::$_singleton = new self;
          }

          return self::$_singleton;
      }



 
      public function get($url,$params = array())
      {
          $req = $this->client->createRequest('GET', $url, $params);
          $response = $this->client->send($req);
              return  $response;

      }
      public function post($url,$params = array() )
      {
          
          $req = $this->client->createRequest('POST', $url, $params);
          $response = $this->client->send($req);
          return $response;
          
      }
      
      

      public function __call($name,$params)
      {
          if(self::$_singleton === null)
          {

              $thi =  static::this();
              return call_user_func_array([$thi->client,$name],$params);

          }else{
              return call_user_func_array([$this->client,$name],$params);
          }

      }

  }