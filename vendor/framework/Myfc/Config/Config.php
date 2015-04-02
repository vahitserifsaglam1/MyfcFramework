<?php
 
 namespace Myfc;
/**
 * Class Config
 *
 *   Sýnýflar içinden configleri çekmek için kullanýlacak sýnýf
 */

  class Config{

      protected $check;

      protected static $configPath;

      protected static $ins;

      protected static $configs = null;

      /**
       *
       *  Baþlatýcý Sýnýf
       *
       *   configs dosyalarýnýn yolunu gösterir
       */

      public function __construct()
      {

          static::$configPath = APP_PATH.'Configs';





      }

      /**
       * @return mixed
       *  Static olarak sýnýfý baþlatmak için kullanýlýr
       */

      public static function boot()
      {

          if(!static::$ins)
          {

              static::$ins = new static();

          }

          return static::$ins;

      }

      /**
       * @param $name
       * @param null $config
       * @return bool|mixed
       *  Conif i çekmek için kullanýlýr
       */

      public static  function get($name,$config = null)
      {

          if(!static::$ins) static::boot();
          
          $path = static::$configPath.'/'.$name.'.php';
          
          if( !isset(static::$configs[$name]))
          {
              if(file_exists($path))
              {
                  
                  static::$configs[$name] = include $path;
                  
              }
             
             
          }
          
          if($config !== null)
          {
              
              return static::$configs[$name][$config];
              
          }else{
              
              return static::$configs[$name];
              
          }

           



      }

      /**
       * @param $name
       * @param null $configs
       * @param null $value
       * @return null
       *
       *  Configi ayarlamak için kullanýlýr
       */
      public static function set($name,$configs = null,$value = null)
      {

          if(!static::$ins) static::boot();

          if( is_array($configs) )
          {

              static::$configs = $configs;

          }
          elseif(  is_string($configs) && $value && $value !== null )
          {

              static::$configs[$configs] = $value;

          }

          return $value;

      }

  }