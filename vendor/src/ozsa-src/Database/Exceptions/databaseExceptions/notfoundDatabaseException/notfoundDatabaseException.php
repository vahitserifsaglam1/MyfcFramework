<?php

  namespace Database\Exceptions\databaseExceptions;

  class notfoundDatabaseExceptions extends  \Exception
  {

      public function __construct( $message )
      {

          $this->message = $message;

      }

  }