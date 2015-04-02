<?php

 namespace Http\Client\Adapter\Socket;

 class Server
 {

     protected $port;

     protected $host;

     protected $timeLimit = 0;

     protected $socket;

     protected $read;

     protected $spawn;

     public function __construct($host = '127.0.0.1', $port = 25003,$timeLimit = 0)
     {

         $this->port = $port;
         $this->host = $host;
         $this->timeLimit = $timeLimit;

         $this->connect();
         $this->read();

     }

     public static function boot($host = '127.0.0.1', $port = 25003,$timeLimit=0)
     {

         return new static($host, $port, $timeLimit);

     }

     protected function connect()
     {

         $host = $this->host;
         $port = $this->port;
         $time = $this->timeLimit;

         set_time_limit($time);

         $this->socket = $socket =  socket_create(AF_INET, SOCK_STREAM,0);

         socket_bind($socket, $host, $port);

         socket_listen($socket,3);

         $this->spawn = $spawn = socket_accept($socket);


     }

     public function write($message)
     {

          $socket = $this->socket;
          $spawn =  $this->spawn;

         return (socket_write($spawn,$message,strlen($message))) ? true:false;

     }

     protected function read()
     {

         $socket = $this->socket;

         $read =  trim(socket_read($socket,1024));

         $this->read = $read;

     }

     public function getRead()
     {
         return ($this->read) ? $this->read:false;
     }

     public function __destruct()
     {

          socket_close($this->socket);
          socket_close($this->spawn);

     }

 }

