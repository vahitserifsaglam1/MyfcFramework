<?php
 namespace Myfc;
 
 
 /**
  * 
  *  Myfc Framework başlatıcı sınıf
  *  
  */
 
 use Myfc\Adapter;
 use Myfc\Singleton;
 use Myfc\Container;
 use Myfc\Config;
 use Exception;
 
 
 class Bootstrap extends Container
 {
    
    public $configs;
     
    public $adapter;
    
    private $getUrl;
    
    
    /**
     * 
     * Adapter atamalar� yapar
     * 
     */
    
    public function __construct()
    {
        
        
         $configs = Config::get('Configs');
         
         $this->configs = $configs;
        
         $this->adapter = Singleton::make('\Myfc\Adapter','Bootstrap');
         
         $this->adapter->addAdapter(Singleton::make('\Myfc\Assets'));
         
         $this->adapter->addAdapter(Singleton::make('\Myfc\Http\Server'));
         
         $this->runServiceProviders($configs['serviceProviders']);

         $this->getUrl = $this->adapter->assests->returnGet();
         
         parent::__construct($this->adapter->server, $configs, $this->getUrl);
         
         
    }


     /**
      * Servis hazırlayıcıları kullanılır hale getirir
      * @param array $providers
      */
    private function runServiceProviders(array $providers = array() )
    {
        foreach($providers as $pro)
        {
                new $pro($this);     
            
        }
        
    }

     /**
      * @return int
      * @throws Exception
      *
      *  Myfc frameworkun versionunu döndürür
      *
      */

     private function version(){

         if(defined("VERSION")) return VERSION;else throw new Exception("MyfcFramework versionu bulunamadı");

     }

    
    
   
 }

?>