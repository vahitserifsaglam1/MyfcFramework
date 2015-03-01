<?php

  namespace Redirect;

  class Redirecter{


      public function __construct()
      {


      }

      public static function boot(){

          return new static();

      }


      public function location($url)
      {

          header("Location:$url");

      }

      public function reflesh($url,$time=2,$message = '')
      {

          header("Refresh:$time, url=$url");

         echo $message;


      }



  }