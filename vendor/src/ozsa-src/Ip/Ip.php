<?php

  use Ip\Block;

 class Ip
 {

      protected $blocker;

      public function __construct()
      {

           if( file_exists( APP_PATH.'Lib/blockip.json'))
           {

               $this->blocker = Block::init( file_get_contents(APP_PATH.'Lib/blockip.json') );

               $this->blocker->boot();

           }

      }

     public static function boot()
     {

          return new static();

     }


     public function getName()
     {

         return __CLASS__;

     }
      public static function getIp()
      {
          if(getenv("HTTP_CLIENT_IP"))
          {
              $ip = getenv("HTTP_CLIENT_IP");

          }
          elseif(getenv("HTTP_X_FORWARDED_FOR"))
          {
              $ip = getenv("HTTP_X_FORWARDED_FOR");
              if (strstr($ip, ','))
              {
                  $tmp = explode (',', $ip);
                  $ip = trim($tmp[0]);
              }
          }
          else
          {
              $ip = getenv("REMOTE_ADDR");
          }

          return $ip;
      }

 }

?>