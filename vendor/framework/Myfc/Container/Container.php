<?php  

  namespace Myfc;
  
  use Myfc\Facade;
  
  use Myfc\Facade\Route;
  
  use Myfc\Router;
  
  use Myfc\Http\Server;
  
  use ReflectionClass;
  
  class Container{
      
      
      public $server;
      
      public $ayarlar;
      
      public $get;
      
      /**
       * Sýnýfý baþlatýr
       * @param \Myfc\Http\Server $server
       * @param array $ayarlar
       * @param array $get
       */
      
      public function __construct( Server $server, array $ayarlar, array $get){
          
          $this->server = $server;
          
          $this->ayarlar = $ayarlar;
          
          $this->get = $get;
         
          
          $this->facedeRegister( $ayarlar['alias'] );
          
          $this->adapter->assests->returnGet();
          
          $this->runRoute();
      }
      
      /**
       * Facadeleri kaydeder
       * @param array $alias
       */
      private function facedeRegister( array $alias )
      {
          
 
          
          Facade::$instance = $alias;          
          
       
      }
      
      /**
       * Contoller oluþturur
       * @param unknown $controller
       * @return unknown|boolean
       */
      
      public function make($controller,$parametres = array())
      {
          
          $controllerPath = APP_PATH."Controller/$controller.php";
          
          if(file_exists($controllerPath))
          {
              
              include $controllerPath;
              
              return (new ReflectionClass($controller))->newInstanceArgs($parametres);
              
          }else{
              
              return false;
          }
          
      }
      
      private function runRoute()
      {
          
          include APP_PATH.'Route.php';
          
          $router = new Router;
          
          $router->run($this, Route::getCollection());
          
      }
      
   
      
  }

  

  
  
  
  
   

?>