<?php


namespace Myfc\Helpers;

trait Http {
    
      /**
       * @return bool
       *  Check request HTTP or Https
       */

      public function isHttps()
      {
          return (array_key_exists('HTTPS', $_SERVER) || $_SERVER['HTTPS'] === 'off');
      }

      /**
       * isteğin ajax olup olmadığını kontrol eder
       * @return type
       */

      public function isAjax()
      {
          return ($_SERVER['X_REQUESTED_WITH'] !== null && $_SERVER['X_REQUESTED_WITH'] === 'XMLHttpRequest');
      }
      
      /**
       * İstek methodunu döndürür
       * @return type
       */
      
      
      public function getRequestMethod(){
          
          return $_SERVER['REQUEST_METHOD'];
          
      }
   
}
