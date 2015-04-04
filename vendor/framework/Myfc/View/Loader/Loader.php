<?php

   namespace Myfc\View;
   
   use Myfc\Config;

  class Loader

  {
       protected $viewPath;

       public function __construct()
       {
           $this->viewPath = VIEW_PATH;
       }

      public function load($name, $allInclude = false,$variables = array() )
      {

          $config = Config::get('Configs','allIncludePath');
          
          if(strstr($name,'.php') )
          {

              $path = $this->viewPath.$name;

          }
          else{

              $path = $this->viewPath.$name.'.php';

          }
          extract($variables);
          if( $allInclude )

          {

              include $this->viewPath.$config.'header.php';

              include $path;

              include $this->viewPath.$config.'footer.php';

          }

          else

          {

              include $path;

          }
      }

  }