<?php

   namespace View;

  class Loader

  {
       protected $viewPath;

       public function __construct()
       {
           $this->viewPath = VIEW_PATH;
       }

      public function load($name, $allInclude = false,$variables = [])
      {

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

              include $this->viewPath.'header.php';

              include $path;

              include $this->viewPath.'footer.php';

          }

          else

          {

              include $path;

          }
      }

  }