<?php

  namespace Myfc\Http\Client\Socket;

  class Client{

       protected $host;

       protected $port;

       protected $socket;

       protected $read;

       protected $timeLimit = 0;

       public function __construct($host = '127.0.0.1', $port = 25003,$timeLimit=0 )
       {

           $this->host = $host;

           $this->port = $port;

           $this->timeLimit = $timeLimit;

       }

       public static function boot( $host = '127.0.0.1', $port = 25003,$timeLimit=0)
       {

            return new static($host, $port, $timeLimit);

       }

      protected  function connect()
      {

          $host = $this->host;

          $port = $this->port;

          $time = $this->timeLimit;

          set_time_limit($time);

          $this->socket = $socket = socket_create(AF_INET, SOCK_STREAM,0);

          $connect = socket_connect($socket, $host, $port);


      }

      protected function read()
      {

          $this->read = $read = socket_read($this->socket,1024);

      }

      public function write($message)
      {

           socket_write($this->socket, $message, strlen($message));

      }

      public function getRead()
      {

          return ($this->read) ? $this->read:false;

      }

      public function __destruct()
      {

          socket_close($this->socket);

      }

  }