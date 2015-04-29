<?php

   namespace Myfc;

   use Myfc\Http\Request;

   use Myfc\Redirect\Redirecter;

   use Myfc\Exceptions\ClassExceptions\undefinedClassException;
  
  class Redirect extends Redirecter
  
  {
      
      

      
      public function __construct()
      {

          $url = Config::get('Configs', 'url');
          
          $request = Request::this();
          
          parent::__construct($request, $url);
          
      }
      
      public static function boot()
      {
          
          return new static();
          
      }
      
      /**
       * bekleme yapmadan y�nlendirme yapar
       * @param string $href
       */
      
      public function location($href= '')
      {
          
          $this->redirect('location', func_get_args());
          
      }
      
      /**
       * Zamana g�re y�nlendirme yapar
       * @param string $href
       * @param number $time
       */
      public function refresh($href = '', $time = 2)
      {
          
          $this->redirect('refresh', func_get_args());
          
      }
      
      /**
       * Eğer $time girilirse girilen süreli olarak, eğer girilmesse direk yönlendirmeli olarak gider
       * @param string $href
       * @param integer $time
       */
      public function to($href, $time = null){
          
          if($time !== null){
              
              $this->refresh($href, $time);
              
          }else{
              
              $this->location($href);
              
          }
          
      }
      
      
      public function __call($method, $parametres)
      {
          
          throw new undefinedClassException(sprintf("%s adında bir method bulunamadı ",$method));
          
      }


  }