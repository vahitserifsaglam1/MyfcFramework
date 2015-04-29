<?php

 namespace Myfc\Http;
 
  use Myfc\Singleton;
 
  class Request
  {

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
      private $_queryString;
      private $_uri;
      private $_requestMethod;
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




      /**
       * @return bool
       *  Check request HTTP or Https
       */

      public function isHttps()
      {
          return (array_key_exists('HTTPS', $_SERVER) || $_SERVER['HTTPS'] === 'off');
      }

      /**
       * @return bool
       *
       *  Check request method
       *
       *  if method = GET return true else false
       */
      


      public function isAjax()
      {
          return ($_SERVER['X_REQUESTED_WITH'] !== null && $_SERVER['X_REQUESTED_WITH'] === 'XMLHttpRequest');
      }
   

 
      public function get($url,$params = array())
      {
          $req = $this->client->createRequest('GET', $url, $params);
          $cek = $this->client->send($req);
              return  $cek;

      }
      public function post($url,$params = array() )
      {
          $req = $this->client->createRequest('POST', $url, $params);
          $cek = $this->client->send($req);
          return $cek;
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