<?php

  namespace Myfc;
 
/**
 * Class Validator
 * ********************************************************
 * MyfcFramework validator sýnýfý
 * gump validator sýnýfý kullanýlýr;
 * @author MyfcFramework
 * *******************************************************
 */
  class Validate
  {
      public  $gump;
      public  $autoValidate = true;
      public static $options;
      public static $functions;
      protected $validateParams;

      /**
       * @param string $options
       *
       *  Validate Klasörünün belirlenmesi ve gerekli klasörün çekilmesi
       */

      public function __construct( )
      {
          $options = require APP_PATH.'Configs/validateConfigs.php';
          $this->set = $options;
          self::$options = $options;
          $this->gump =  Desing\Single::make('\GUMP');#new GUMP;
          if($this->autoValidate)
          {
               $appFolder = $options['validateFolder'];

               $files = glob($appFolder."/*",GLOB_NOSORT);


               $types = [];

              foreach($files as $key)
              {
                  $notphp = str_replace(".php","",$key);
                  $explode = explode("/",$notphp);
                  $end = end($explode);
                  $types[ $end ] = require $key;
              }
            $this->validateParams = $types;



          }

      }



      public static function boot(   )
      {

          return new static();

      }
      public static function make($veri,array $filters = array(),array $rules = array())
      {
          $thi =  new static(self::$options);
          $s = $veri->gump->filter($veri,$filters);

          $validate = $thi->gump->validate(
              $veri,$rules
          );

          if($validate) return true;else return $validate;
      }

      public static function makro($name,Callable $call)
      {
          $validate = new static(self::$options);
          $validate = $validate->gump;
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
       *  Ýsme göre dosyadan deðerler okunup kontrol edilmesi
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

                      $return = filter_var(str_replace(array('<script>',"'",'"','</script>','<?','?>',' = ','=',"or","select"),'',htmlentities(htmlspecialchars(strip_tags($value)))),FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_LOW);

              }
          }
          }else{
              $return = filter_var(str_replace(array('<script>',"'",'"','</script>','<?','?>',' = ','=',"or","select"),'',htmlentities(htmlspecialchars(strip_tags($validate)))),FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_LOW);
          }
           return $return;
      }

      /**
       * @param $validate
       * @return mixed
       */
      public static function validateOzsa($validate)
      {
          if(is_array($validate))
          {
              foreach($validate as $key => $value)
              {

                  if(is_array($value))
                  {
                      self::validateOzsa($value);
                  }else{

                      $return = filter_var(str_replace(array('<script>',"'",'"','</script>','<?','?>',' = ','=',"or","select"),'',htmlentities(htmlspecialchars(strip_tags($value)))),FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_LOW);

                  }
              }
          }else{
              $return = filter_var(str_replace(array('<script>',"'",'"','</script>','<?','?>',' = ','=',"or","select"),'',htmlentities(htmlspecialchars(strip_tags($validate)))),FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_LOW);
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