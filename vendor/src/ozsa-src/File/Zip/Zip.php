<?php

 namespace Html;

 use Exceptions\ClassExceptions\MethodExceptions\undefinedMethodException;

 /**
  * Class Zip
  * @package Html
  *
  */

 class Zip
 {
     protected $zip;

     protected static $boot;

     /**
      *
      *  Sınıfın başlangıçı ve ZipArchive sınıfının çağrılması
      *
      */

  public function __construct()
  {

      if (class_exists('ZipArchive')) {

          $this->zip = new \ZipArchive;

      }

  }

      /**
       *
       *  Tüm fonksiyonlar dinamik olarak ziparchive sınıfından çağrılması
       *
       */

     public static function boot()
     {

         if(!static::$boot)
         {

             static::$boot = new static;

         }

         return static::$boot;

     }

     /**
      * @param $name
      * @param $params
      * @return bool|mixed
      */

       public function __call( $name,$params ){


        if( method_exists($this->zip,$name) )
        {

            return call_user_func_array(array($this->zip,$name),$params);

        }else{

            throw new undefinedMethodException(" $name adında bir fonksiyon bulunamadı ");

            return false;

        }

      }

     /**
      * @param $name
      * @param $params
      * @return bool|mixed
      *
      *
      *  Static olarak çağrılan methodların düz olarak getirilmesi
      *
      */

     public static function __callStatic($name, $params)
     {

         $thi = static::boot();

         if(method_exists($thi->zip,$name))
         {

             return call_user_func_array(array( $thi->zip, $name ),$params);

         }else{

             throw new undefinedMethodException(" $name adında bir fonksiyon bulunamadı ");

             return false;

         }

     }




 }