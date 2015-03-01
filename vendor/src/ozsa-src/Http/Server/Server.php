<?php

 namespace Http;

  Class Server {

      protected  $configs;

      protected  $url;

      protected  $array = [

          'useragent' => 'HTTP_USER_AGENT',
          'referer'   => 'HTTP_REFERER',
          'host'      => 'HTTP_HOST',
          'reditect'  => 'REDIRECT_URL',
          'serverip'  => 'SERVER_ADDR',
          'REMOTE_ADDR' => 'userip'

      ];

      public function __construct(){


      $this->configs =  require APP_PATH.'Configs/Configs.php';

          $this->url = $this->configs['URL'];


      }

      public function returnUrl()
      {

          return $this->url;

      }

      public static function boot()
      {

          return new static();

      }

      public function __destruct(){

              $this->configs = null;


      }

      public function get($name = 'HTTP_HOST')
      {

          if( isset( $_SERVER[$name] ) )
          {

              return $_SERVER[$name];

          }

      }

      public function getName()
      {

          return "server";

      }

      public function __get($name)
      {

          if(isset($this->array[$name]))
          {

              return $this->array[$name];

          }
          else{

              $big = mb_convert_case($name,MB_CASE_UPPER,'UTF-8');

              if( isset($_SERVER[$big]) )
              {

                  return $_SERVER[$big];

              }else{

                  throw new \Exceptions\VariableExceptions\undefinedVariableExcepiton(sprintf("%s adında bir server değişkeni bulunamadı "));

              }

          }

      }

  }