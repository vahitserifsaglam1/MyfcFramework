<?php

 namespace Myfc;
 
 use Myfc\Singleton;

  class Facade{

       public static $instance = array();

      /**
       * @return mixed
       *  Classı almak için kullanılan method
       */
       protected static function getFacedeRoot()
       {

           if( $root = static::resolveFacede() ) return $root;

       }

      /**
       * @return mixed
       * @throws \Exception
       *
       *   Sınıfı KOntrol eder
       */
      protected static function resolveFacede()
      {

          return static::resolveFacedeClassName(static::getFacadeClass());

      }

      /**
       * @throws \Exception
       *  Alt sınıflarda sınıfın ismini döndürmesi için kullanılır
       */
      protected  static function getFacadeClass(){

          throw new \Exception("Facede sınıfı kendi kendini çağıramaz");

      }

      /**
       * @param $name
       *
       *  Sınıfın olup olmadığını kontrol ediyor
       */

      protected static function resolveFacedeClassName($name)
      {

         
       if( is_object($name) ) return $name;

          if( isset(static::$instance[$name] ) )
          {
              
              return static::$instance[$name];

          }

      }

      /**
       * Tüm sınıfları temizler
       */

      public static function clearFacades()
      {

          static::$instance = array();

      }

      /**
       * @param $name
       *
       *  İsme göre temizleme işlemi
       */

      public static function clearFacade($name)
      {

          if(isset(static::$instance[$name])){
              static::$instance[$name] = $name;
          }

      }

      /**
       * @param $method
       * @param $parametres
       * @return mixed
       *  Dönen sınıfdan istediğimiz methodu static olarak çağırmaya yarar
       */
      public static function __callStatic( $method, $parametres )
      {



           $instanceName = static::getFacedeRoot();

   
           if(!is_object($instanceName))
           {
               
               $instance = Singleton::make($instanceName);
               
               static::$instance[$instanceName] = $instance;
               
               
           }else{
               
               $instance = $instanceName;
               
           }
           
           
           return call_user_func_array(array($instance,$method), $parametres);
           
          /* $reflectionMethod = new ReflectionMethod($instance,$method);
           return  $reflectionMethod->invokeArgs($instance,$parametres); */

      }

  }

