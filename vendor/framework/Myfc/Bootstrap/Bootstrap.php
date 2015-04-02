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
 use Myfc\Session\Starter;
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

         $this->getUrl = $this->adapter->assests->returnGet();
         
         $this->urlChecker();
         
         $this->sessionStart();
         
         $this->languageInstall();
         
         $this->runServiceProviders($configs['serviceProviders']);
         
         parent::__construct($this->adapter->server, $configs, $this->getUrl);
        
    } 
    
    private function urlChecker()
    {
    

        $get = $this->getUrl;
     
        
        if(!isset($get['url']))
        {
            
            $this->adapter->assests->setGet(array('url' => 'index'));
            
        }
    
    }
    
    /**
     * Session sýnýfýný yapýlandýrýr
     */
    private function sessionStart()
    {
        
        $this->adapter->addAdapter(new Starter());
        
    }
    
    /**
     * Dil sýnýfýný yapýlandýrýr
     */
    
    private function languageInstall()
    {
        
        $this->adapter->addAdapter( Singleton::make('\Myfc\Language'));
        
    }
    
    /**
     * 
     */
    private function runServiceProviders(array $providers = array() )
    {
        
          
        foreach($providers as $pro)
        {

          
                
                $this->adapter->addAdapter(new $pro());
               
            
            
        }
        
        
    }
    
    
   
 }

?>