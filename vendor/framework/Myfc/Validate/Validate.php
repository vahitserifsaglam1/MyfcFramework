<?php

  namespace Myfc;
  /**
 * Class Validator
 * ********************************************************
 * MyfcFramework validator s�n�f�
 * Gump validator sınıfı kullanılır
 * @author MyfcFramework
 * @package Myfc
 * *******************************************************
 */
  use Myfc\Singleton;

  class Validate
  {
      /**
       *
       * @var GUMP 
       */
      public  $gump;
      
      /**
       *
       * @var boolean 
       */
      public  $autoValidate = true;
      public static $options;
      public static $functions;
      protected $validateParams;

      /**
       * @param string $options
       *
       *  Validate Klas�r�n�n belirlenmesi ve gerekli klas�r�n �ekilmesi
       */

      public function __construct( )
      {
          
          $this->gump = Singleton::make('\GUMP');#new GUMP;     

      }


      /**
       * S�n�f� ba�lat�r
       * @return \Myfc\Validate
       */

      public static function boot(   )
      {

          return new static();

      }
      
      /**
       * Gump kullanarak filtreleme yapar
       * @param unknown $veri
       * @param array $filters
       * @param array $rules
       * @return boolean|unknown
       */
      public function make($veri,array $filters = [],array $rules =[])
      {
          
          $s = $veri->gump->filter($veri,$filters);

          $validate = $this->gump->validate(
              $veri,$rules
          );

          if($validate) return true;else return $validate;
      }

      /**
       * S�n�fa ilerde �a�r�labilmesi i�in fonksiyon eklemesi yapar
       * @param String $name
       * @param callable $call
       */
      public  function makro($name = '',Callable $call)
      {
          $validate = $this->gump;
          if(is_callable($call))
          {
              static::$functions[$name] = Closure::bind($call, null, get_class());
          }

      }

      /**
       * @param $veri
       * @param $param
       * @return bool
       * @throws Exception
       *
       *  Veriler okunup validate edilir
       */
      public function validate($veri,$param)
      {
          $files = $this->validateParams;

          $veri = $this->gump->filter($veri, $files[$param]['filters']);

          $validated = $this->gump->validate(
              $veri, $files[$param]['rules']
          );

          if($validated)
          {
              return true;
          }else{
              return $validated;
          }

      }

      /**
       * @param $validate
       * @return mixed
       */
      public function validatorOzsa($validate)
      {
          if(is_array($validate))
          {
              foreach($validate as $key => $value)
              {

                  if(is_array($value))
                  {
                      $this->validatorOzsa($value);
                  }else{

                      $return = filter_var(str_replace(['<script>',"'",'"','</script>','<?','?>',' = ','=',"or","select"],'',htmlentities(htmlspecialchars(strip_tags($value)))),FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_LOW);

              }
          }
          }else{
              $return = filter_var(str_replace(['<script>',"'",'"','</script>','<?','?>',' = ','='," or "," select ", " and ","  AND ", " OR ", " SELECT "],'',htmlentities(htmlspecialchars(strip_tags($validate)))),FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_LOW);
          }
           return $return;
      }

      public static function __callStatic($name,$params)
      {
          if(isset(static::$functions[$name]))
          {
              return call_user_func_array(static::$functions[$name],$params);
          }
      }
  }