<?php
 namespace Myfc;
 
 
 /**
  * 
  *  Myfc Framework başlatıcı sınıf
  *  
  */
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
        
         $this->adapter = $this->singleton('\Myfc\Adapter','Bootstrap');
         
         $this->adapter->addAdapter($this->singleton('\Myfc\Assets'));
         
         $this->adapter->addAdapter($this->singleton('\Myfc\Http\Server'));
         
         $this->runServiceProviders($configs['serviceProviders']);

         $this->getUrl = $this->adapter->assests->returnGet();
         
         parent::__construct($this->adapter->server, $configs, $this->getUrl);
         
         
    }


     /**
      * Servis hazırlayıcıları kullanılır hale getirir
      * @param array $providers
      */
    
    private function runServiceProviders(array $providers = [] )
    {
        foreach($providers as $pro)
        {
                $this->maked[] = $this->runServiceProvider($pro,$this);    
            
        }
        
    }
    
    /**
     * Yürütmeyi yapar
     * @param type $name
     * @return mixed
     */

    public function runServiceProvider($name, $param){
        
        return new $name($param);
        
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