<?php

   namespace Database\Exceptions\QueryExceptions;


   class unsuccessfullFindTableException extends \Exception

   {


       public function __construct( $message )
       {

           $this->message = $message;

       }


   }