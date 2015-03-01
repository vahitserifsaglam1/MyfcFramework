<?php

   namespace Database\Exceptions\QueryExceptions;

   class noRowCountException extends \Exception
   {

       public function  __construct(  $query = '' )

       {

          $this->message =   $query.' ile yaptığınız sorgudan hiçbir satır dönmedi';

       }

   }