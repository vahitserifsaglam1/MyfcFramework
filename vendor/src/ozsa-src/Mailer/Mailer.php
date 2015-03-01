<?php

  class Mailer {
      public static function send(Callable $call)
      {
           $message = Desing\Single::make('Mail\MailerSrc\Mail',$options);

           return $call($message);
      }
  }