<?php

  namespace Myfc\Exceptions\ClassExceptions\MethodExceptions;
 
  use Exception;

  class undefinedMethodException extends  Exception
  {

      public function __construct( $message ){

          $this->message = $message;

      }

  }