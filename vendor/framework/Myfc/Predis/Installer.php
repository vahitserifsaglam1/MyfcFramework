<?php
  namespace Myfc\Predis;
  use Myfc\Config;
  use PredisClient;
  use Exception;
  use PredisAutoloader;
  

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
            try{
                
                PredisAutoloader::register();
                static::$installed = true;;
            }
            
            catch(Exception $e)
            {
                static::$installed = false;
                echo $e;
            }

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

               $redis = new PredisClient( $configs );

           }catch(Exception $e)
           {
               
               $redis = $e->getMessage();
               
           }
           
           
           return $redis;


       }



  }