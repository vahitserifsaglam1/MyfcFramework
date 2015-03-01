<?php

  namespace Exceptions\ClassExceptions\MethodExceptions;


  class undefinedMethodException extends  \Exception
  {

      public function __construct( $message ){

          $this->message = $message;

      }

  }