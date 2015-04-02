<?php

  namespace Myfc\Redirect;

   use Myfc\Config;
  class Redirecter{


      public function __construct()
      {


      }

      public static function boot(){

          return new static();

      }

       protected function genarete($url)
       {

           $urls = Config::get('Configs','URL');

           return $urls.$url;

       }

      public function location($url)
      {
          $url = $this->genarete($url);
          header("Location:$url");

      }

      public function reflesh($url,$time=2,$message = '')
      {
          $url = $this->genarete($url);
          header("Refresh:$time, url=$url");

         echo $message;


      }



  }