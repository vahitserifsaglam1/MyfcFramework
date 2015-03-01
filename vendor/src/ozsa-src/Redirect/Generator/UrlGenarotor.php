<?php

 namespace Redirect;

 class Generator
 {

     public $baseurl;

     private $url;

     private $base;

     protected $server;

  protected $filesystem;

  public function __construct( $baseurl = '')
  {

        $this->server = \Desing\Single::make( '\Http\Server' );

        $this->filesystem = \Filesystem::boot('Local');

        $base = require APP_PATH.'Configs/Configs.php';

        $this->base = $this->server->returnUrl();



  }

  public static function boot( $baseurl = '')
  {

    return new static($baseurl);

  }

  public function generator( $url )
  {

       $this->url = $url;

       return $this->urlCheck( $url );

  }

  private function urlCheck( $url )
  {

      if($this->controllerCheck( $url )){

          return true;

      }

  }

  private function controllerCheck( $name )
  {

    $controller = array_slice(\App\App::urlParse(),0,1);

    $kontrol = array_get($controller,0);


    $name = $kontrol.'.php';

     if( \App\App::controllerCheck($name))
     {

          $return = true;

         if(isset($controller[2]) && method_exists($kontrol,$controller[1]))
         {

            $return = true;

         }else{

             $return = true;

         }

     }else{

         $return = false;

     }

      return $return;



  }

     public function requestCheck( $request, $url )
     {


         $get = $request->get( $this->base.$url);

         if( $get->getStatusCode() === 200 )
         {

             return true;

         }else{

             return false;
         }




     }





 }