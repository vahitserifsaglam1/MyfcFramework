<?php

  namespace Myfc\Exceptions\ClassExceptions\PropertyExceptions;

  use Exception;
  
  class undefinedPropertyException extends Exception
  {

      public function __construct( $message ){
          
          $this->message = $message;
          
      }

  }