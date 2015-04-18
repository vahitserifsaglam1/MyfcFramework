<?php 

  namespace Myfc;
  
  use Myfc\Session\SessionInterface;
  
  class Session{
      
      private $configs;
      
      private $connector;
      
      private $driverList = array(
          
          'php' => 'Myfc\Session\Connector\php',
          'file' => 'Myfc\Session\Connector\file'
          
      );
      
      /**
       * Sınıfı başlatır
       * @param array $configs
       */
      
      public function __construct( array $configs = null )
      {
          
          if($configs === null)
          {

               $configs = Config::get('strogeConfigs', 'Session');
             
          }
          
          $this->configs = $configs;
          
          $this->connector =   $this->connectToConnector( $this->configs );
          
      }
      
      /**
       * Başlatıcı sürücüye bağlanır
       * @param array $configs
       * @return unknown
       */
      
      private function connectToConnector(array $configs)
      {
          
            $driver = $configs['driver'];
            
            $default = $configs['default'];
            
            if(!isset($this->driverList[$driver]))
            {
                
                $driver = $this->driverList[$default];
                
            }
            
            if($connector = $this->driverList[$driver])
            {
                
                $connector = new $connector($configs);
                
                if($connector->check())
                {
                    
                    return $connector;
                    
                }
                
            }
            
          
      }
      
      /**
       *  
       *  s�n�fa yeni bir connector ekler, autocheck true ise otomatik o driver� se�er
       *  
       *    $call dan d�nen de�er bir SessionInterface e ait olmal�d�r,
       *    
       *    $name eklentinin ismidir
       * 
       * @param string $name
       * @param callable $call
       * @param boolean $autocheck
       * @return boolean
       */
      public function extension($name, callable $call, $autocheck = false)
      {
          
          $return = $call();
          
          if($return instanceof SessionInterface)
          {
              
              $this->driverList[$name] = get_class($return);
              
              if($autocheck)
              {
                  
                  $this->driver($name);
                  
              }
              
              return true;
              
          }else{
              
              return false;
              
          }
          
      }
      
      /**
       * driver seçimi yapar
       * @param string $name
       */
      
      public function driver($name)
      {
          
          if(isset($this->driverList[$name]))
          {
              
              $this->configs = $this->configs['driver'] = $name;
              
              $this->connectToConnector($this->configs);
              
          }
          
      }
      
      /**
       * Dinamik olarak fonksiyon �a��rmakta kullan�l�r
       * @param string $method
       * @param array $parametres
       * @return mixed
       */
      
      private function call($method,array $parametres)
      {
          
          if(is_callable(array($this->connector,$method)) && method_exists($this->connector, $method))
          {
              
              return call_user_func_array(array($this->connector, $method), $parametres);
              
          }
              
          
      }
      
      
     
      
      public function __call($method, $parametres){
         
          
          return $this->call($method, $parametres);
          
      }
      
  }
 
      
      
      
?>