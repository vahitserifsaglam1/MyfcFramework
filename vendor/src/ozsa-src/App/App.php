<?php
    namespace App;

    class App
    {
        /**
         * @var array|bool Ayarların tutulacağı değişken
         */
        protected $settings = false;
        private $db = false;
        private $request;
        private $data = null;
        public $validator;
        public $boots;
        protected $adapters;

        /***
         * @param $pathOptions
         * @param $configs
         *
         *  Başlatıcı fonksiyon: Adapatera gerekli adapterların eklenmesi
         */

        public function __construct($pathOptions,$configs)
        {
            $this->settings = ['path' => $pathOptions,
            'configs'=> $configs];

            $this->adapters = \Desing\Single::make('Adapter\Adapter','start');

            $this->adapters->addAdapter( \Desing\Single::make('\App\Assets'),true);

            $this->adapters->addAdapter( \Desing\Single::make( '\Session\Starter'));

            $this->adapters->addAdapter( \Desing\Single::make( '\Http\Server') );



             #   $this->adapters->alAdabtersBoot();


            $this->getRequest();

        }
       public function setErrorReporting()
       {
           $configs = require APP_PATH.'Configs/Error.php';

           error_reporting($configs['Reporting']);
           return null;
       }

        /**
         * @return array
         *  Urli ayarıma fonksionu
         */

        public static function urlParse()
        {
            return explode("/",$_GET['url']);
        }

        public function requestControl()
        {

             $url = $this->adapters->Assets->returnGet()['url']; #Assets::returnGetStatic()['url'];


            if(!isset($url)) $url = 'index';

            if(strstr($url,'.php'))
            {
                $url = str_replace('.php','',$url);
            }

            if(!strstr($_GET['url'],'/'))
            {
                $url .= "/";
            }

            $ex = explode("/",$url);

            define('URL',$url);

            return $ex;
        }

        public function paramsCheck( $ex )
        {
            if(isset($ex[1]))
            {
                $function = $ex[1];
            }
            if(isset($ex[2]))
            {
                unset($ex[0]);
                unset($ex[1]);
                $params=$ex;

            }else
            {
                $params = array();
            }

            return $params;
        }

        public function getRequest()
        {


              $configs = $this->settings['path'];

              $appPath =  rtrim(APP_PATH,'/');

              $systemPath =  rtrim(SYSTEM_PATH,'/');

              $ex = $this->requestControl();

              @$view = $ex[0];

              @$function = $ex[1];

              $params = $this->paramsCheck( $ex );



              if( $view != $appPath && $view != $systemPath && !strstr($_SERVER['REQUEST_URI'], 'public.php'))
              {


                  $path  =  $appPath."/Controller/$view.php";

                  if(isset( $path ) && file_exists($path) )
                  {


                      include $path;
                      $class = \Desing\Single::make($view);

                      if( count($params)> 1)
                      {

                          if(isset($function))call_user_func_array(array($class,$function),$params);

                      }else{


                          $class->$function();

                      }



                  }else{

                      $response = \Desing\Single::make('\Http\Response','Böyle bir sayfa bulunamadı',404);

                      $url = \Config::get('Configs','URL');

                      $response ->reflesh($url."index");

                  }



                 # $render->render($appPath."/Views/".$view.".php",$configs);

              }else{

                  $response = \Desing\Single::make('\Http\Response','Bu sayfalara giremezsiniz',404);

                  $url = \Config::get('Configs','URL');

                  $response ->reflesh($url."index");


              }
        }

        public static function controllerCheck($name)
        {

            return (file_exists( APP_PATH."/Controller/$name.php")) ? true:false;

        }
        public function __destruct()
        {
            \Session::flush();
        }

    }


