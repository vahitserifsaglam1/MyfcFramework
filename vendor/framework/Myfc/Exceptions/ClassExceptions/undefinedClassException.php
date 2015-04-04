<?php


  namespace Myfc\Exceptions\ClassExceptions;
  
  use Exception;

  class undefinedClassException extends  Exception

  {

      public function __construct( $message ){ $this->message = $message;}

  }