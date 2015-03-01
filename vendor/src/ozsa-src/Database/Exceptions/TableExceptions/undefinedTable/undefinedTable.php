<?php

 namespace Database\Exceptions\TableExceptions;

    class undefinedTableException extends \Exception
    {

           public function __construct( $message  )

           {
               if( is_string($message) )

               {
                   $this->message = $message;

               }
               elseif ( is_callable( $message ))
               {

                   $this->message =  $message();

               }


           }
    }