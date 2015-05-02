<?php 

  namespace Myfc;
  
  use Myfc\Session\SessionInterface;
  use Myfc\Helpers\DriverManager;
  class Session extends DriverManager{
      

    
      
      /**
       * Sınıfı başlatır
       * @param array $configs
       */
      
      public function __construct( array $configs = null )
      {
          
          $this->setDriverList([
          
          'php' => 'Myfc\Session\Connector\php',
          'file' => 'Myfc\Session\Connector\file'
          
      ]);
          if($configs === null)
          {

               $configs = Config::get('strogeConfigs', 'Session');
             
          }
          
          $this->boot($configs);
          
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
              $this->addDriver($name, $return); 
              if($autocheck)
              {
                  
                  $this->connectDriver($name);
                  
              }
              
              return true;
              
          }else{
              
              return false;
              
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
          
          if(is_callable([$this->getDriver(),$method]) && method_exists($this->getDriver(), $method))
          {
              
              return call_user_func_array([$this->getDriver(), $method], $parametres);
              
          }
              
          
      }
      
      
     
      
      public function __call($method, $parametres){
         
          
          return $this->call($method, $parametres);
          
      }
      
  }
 
      
      
      
?>