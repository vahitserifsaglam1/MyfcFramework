<?php

     class Check
     {

          public static function validateHuman()
          {

              if($_SERVER['HTTP_USER_AGENT'] && $_SERVER['HTTP_ACCEPT'] && $_SERVER['HTTP_CONNECTION'] == 'keep-alive' &&
                  $_SERVER['REMOTE_ADDR'] && $_SERVER['REMOTE_ADDR'] ==  \Ip\Get::Getip() ) return true;else return false;

          }


         public static function isAjax()
         {

             return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
             $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest' ;

         }



     }
?>