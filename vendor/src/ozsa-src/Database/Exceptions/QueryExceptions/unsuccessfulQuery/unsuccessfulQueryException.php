<?php

 namespace Database\Exceptions\QueryExceptions;

  class unsuccessfulQueryException extends \Exception

  {


       public function __construct( $message, $query = ' ')

       {

           $this->message =  $query." ile yapmış olduğunuz sorgu başarısız oldu, dönen sonuç : ".$message;



       }

  }