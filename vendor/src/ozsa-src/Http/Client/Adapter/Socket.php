<?php

  namespace Http\Client\Adapter;

  use Http\Client\Adapter\Socket\Server;

  use Http\Client\Adapter\Socket\Client;

  use Exceptions\ClassExceptions\MethodExceptions\undefinedMethodException;

  class Socket
  {

       const SERVER = 'server';

       const CLIENT = 'client';

       protected $server;

       protected $client;

       protected $selected;

      public function __construct($type, $params)
      {

          if(function_exists('socket_create'))
          {
              $this->selected = $type;
              extract($params);
              switch($type)
              {

                  case 'server':
                      $this->server = new Server($host, $port);
                      break;
                  case 'client':
                      $this->client = new Client($host,$port);
                      break;

              }

          }else{

              throw new \Exception( 'Socket desteği olmadan bu sınıfı kullanamassınız' );

          }

      }

      public function __call($name, $params)
      {
          $selected = $this->selected;
          if(method_exists($this->$selected,$name))
          {

              return call_user_func_array(array($this->selected,$name),$params);

          }else{

               throw new undefinedMethodException( sprintf(" %s Bu fonksiyon %s içinde bulunamadı",$name,$selected) );

          }

      }

  }

