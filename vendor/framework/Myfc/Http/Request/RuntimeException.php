<?php

   namespace Myfc\Http;

   use Myfc\Exception;

  class RuntimeException extends Exception{


      public function __construct($message = '')
      {

          $this->message = $message;

      }

  }