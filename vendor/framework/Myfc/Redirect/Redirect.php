<?php

  namespace Myfc;

   use Myfc\Http\Request;

   use Myfc\Redirect\Generator as urlGenarator;

   use Myfc\Redirect\Redirecter;

   use Exceptions\ClassExceptions\MethodExceptions\undefinedMethodException;


  class Redirect
  {

      protected $generator;

      protected $request;

      protected static $static;

      protected $message;

      protected  $reditecter;

      protected static $boot;


     public function  __construct()
     {

         if(!$this->request)
         {

             $this->request = Request::this();


         }


         if(! $this->reditecter )
         {

             $this->reditecter = Redirecter::boot();

         }

     }


    public static function boot(){

      self::$static =  new static;

      return self::$static;

    }

      /**
       * @param $url
       * @param int $time
       */

    public static function  intended( $url, $time=0 )
    {

       if( !static::$static )
       {

         static::boot();

       }

        if( $time>0 )
        {
            static::$static->reditecter->reflesh($url,$time);
        }else{

            static::$static->reditecter->location($url);
        }





    }

      public function __call( $name, $params )
      {

          if( method_exists($this->reditecter,$name))
          {

              return call_user_func_array(array($this->reditecter,$name),$params);

          }
          else{

              throw new undefinedMethodException(  "$name  adında bir fonksiyon bulunamadı");

          }

      }


  }