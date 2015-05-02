<?php
  namespace Myfc\Predis;
  use Myfc\Config;
  use Predis\Client;
  use Exception;
 

  class  Installer{
      
      /**
       * 
       * Sınıfın daha önceden kurulup kurulmadığına bakar
       * @var boolean 
       */
      protected static $installed = false;

        /**
         *  S�n�f� ba�lat�r
         */
        public static function boot(  )
        {
   

        }

        /**
         * Yeni bir predis client i oluşturup döndürür
         * @param string $configs
         * @return \PredisClient
         */
        
       public static  function create( $configs = null)
       {
           if(static::$installed == false) static::boot();
           if($configs == null)
           {
              $configs = Config::get('strogeConfigs','predis'); 
           }

           try{

               $redis = new Client( $configs );

           }catch(Exception $e)
           {
               
               $redis = $e->getMessage();
               
           }
           
           
           return $redis;


       }



  }