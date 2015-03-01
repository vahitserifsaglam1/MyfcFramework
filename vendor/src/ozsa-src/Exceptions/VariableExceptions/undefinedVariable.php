<?php

  namespace Exceptions\VariableExceptions;


  class undefinedVariableExcepiton extends \Exception

  {

       public function __construct( $message = '' ){

           $this->message = $message;

       }

  }

