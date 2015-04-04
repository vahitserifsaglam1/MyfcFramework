<?php
 namespace Myfc;
 
 
 /**
  * 
  *  Myfc Framework baþlatýcý sýnýf
  *  
  */
 
 use Myfc\Adapter;
 use Myfc\Singleton;
 use Myfc\Container;
 use Myfc\Http\Server;
 use Myfc\Config;
 use Myfc\Facade\Session;

 class Bootstrap extends Container
 {
    
    public $configs;
     
    public $adapter;
    
    private $getUrl;
    
    
    /**
     * 
     * Adapter atamalarý yapar
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
     * 
     *  Servis hazýrlayýcýlarýný hazýrlar
     * 
     */
    private function runServiceProviders(array $providers = array() )
    {
     
          
        foreach($providers as $pro)
        {

                $this->maked[] = $pro;
                new $pro($this);
               
            
        }
        
      
        
        
    }

    
    
   
 }

?>