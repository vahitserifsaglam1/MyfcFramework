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
           
           $this->findMethod();

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
       * @return null
       *  Return Query String
       */
      public function getQueryString()
      {
          if ($this->_queryString === null) {
              $this->_queryString = self::getVar('QUERY_STRING', false);
          }
          return $this->_queryString;
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
      
      public function findMethod()
      {
          
          $method = $_SERVER['REQUEST_METHOD'];
          
          $this->_requestMethod = $method;
          
      }
      
      public function returnMethod()
      {
          
          return $this->_requestMethod;
          
      }
      
      public function isGet()
      {
          return ($this->_requestMethod === 'GET') ? true:false;
      }
      public function isPost()
      {
          return $this->_requestMethod === 'POST';
      }
      public function isDelete()
      {
          return $this->_requestMethod === 'DELETE';
      }
      public function isPut()
      {
          return $this->_requestMethod === 'PUT';
      }
      public function isHead()
      {
          return $this->_requestMethod === 'HEAD';
      }
      public function isAjax()
      {
          return ($_SERVER['X_REQUESTED_WITH'] !== null && $_SERVER['X_REQUESTED_WITH'] === 'XMLHttpRequest');
      }
      public function httpGet($index = null)
      {
          if ($index === null) {
              return $_GET;
          }
          return (array_key_exists($index, $_GET)) ? $_GET[$index] : null;
      }
      public function httpPost($index = null)
      {
          if ($index === null) {
              return $_POST;
          }
          return (array_key_exists($index, $_POST)) ? $_POST[$index] : null;
      }

      public function getUri()
      {
          if ($this->_uri === null) {
              $_requestUri = filter_var(self::getVar('REQUEST_URI', '/'), FILTER_SANITIZE_URL);
              if ($_requestUri === $_SERVER['SCRIPT_NAME']) {
                  $_uri = '/';
              } else {

                  $_uri = (mb_strpos($_requestUri, $_SERVER['SCRIPT_NAME']) === 0)
                      ? substr_replace($_requestUri, '', 0, mb_strlen($_SERVER['SCRIPT_NAME']))
                      : $_requestUri;


              }

              if (($_queryString = $this->getQueryString()) !== false) {
                  $this->_uri = trim(str_replace('?' . $_queryString, '', $_uri), '/');
              } else {
                  $this->_uri = trim(rtrim($_uri, '?'), '/');
              }

          }

          return (is_string($this->_uri) === false || mb_strlen($this->_uri) === 0) ? '/' : $this->_uri;
      }


      public static function getVar($key, $default = null)
      {
          return (array_key_exists($key, $_SERVER)) ? $_SERVER[$key] : $default;
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
              return call_user_func_array(array($thi->client,$name),$params);

          }else{
              return call_user_func_array(array($this->client,$name),$params);
          }

      }

  }