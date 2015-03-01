<?php


   class incorrentValueTypeException extends \Exception
   {

        public function __construct( $message = '')
        {

             $this->message = $message;

        }

   }