<?php
  namespace Myfc\Predis;
  
  use Myfc\Config;
  
  use PredisClient;
  
  use Exception;
  
  use PredisAutoloader;

  class  Installer

  {
      protected static $installed = false;

        public function boot(  )
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

       public static  function create( $configs = null)
       {
           if(static::$installed == false) static::boot();
           if($configs == null)
           {
              $configs = Config::get('databaseConfigs','predis'); 
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