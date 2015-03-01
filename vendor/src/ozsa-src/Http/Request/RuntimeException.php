<?php

  namespace Http;


  class RuntimeException extends \Exception{


      public function __construct($message = '')
      {

          $this->message = $message;

      }

  }