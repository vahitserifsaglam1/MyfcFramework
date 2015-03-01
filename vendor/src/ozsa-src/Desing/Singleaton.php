<?php

  namespace Desing;

  class Single
  {

      public static $siniflar;

      public static $sinifSay = 0;


      public static function make ( $sinif )

      {



               if( !isset (self::$siniflar[$sinif] ) ) {

                   $parametres = static::unsetter(func_get_args());

                   self::$siniflar[$sinif] = (new \ReflectionClass($sinif))->newInstanceArgs($parametres);
                   self::$sinifSay++;
               }
               return self::$siniflar[$sinif];




      }


      public static function unsetter( array $array = array() )
      {

          unset($array[0]);

          return  $array;

      }





      }


