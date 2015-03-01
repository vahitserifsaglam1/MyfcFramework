<?php

 namespace Database\Exceptions\MethodExceptions;

 class undefinedMethodException extends \Exception

 {

     public function __construct( $message = ' ')
     {

          $this->message = $message;



     }


 }