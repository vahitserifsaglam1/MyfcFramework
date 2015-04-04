<?php

  namespace Myfc\Exceptions\VariableExceptions;

   use Exception;

  class undefinedVariableExcepiton extends Exception

  {

       public function __construct( $message = '' ){

           $this->message = $message;

       }

  }

