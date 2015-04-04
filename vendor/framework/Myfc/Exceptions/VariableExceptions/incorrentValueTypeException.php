<?php

  namespace Myfc\Exceptions\VariableExceptions;
  
  use Exception;
 
   class incorrentValueTypeException extends Exception
   {

        public function __construct( $message = '')
        {

             $this->message = $message;

        }

   }