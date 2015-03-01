<?php

  class MainController extends Database {

      /**
       *
       *  main controller dosyası
       *
       * @var string
       *
       */
      public $_modal;
      public $_assets;
      public $_view;

      public function __construct()
      {
         $this->_assets = Desing\Single::make('\App\Assets');
          $this->_view =  Desing\Single::make('\View\Loader');
      }
      public function _modal($name)
      {
          $modalPath = APP_PATH.'Modals/'.$name.'.php';
          $modalName = $name;

          if(file_exists($modalPath)){

              include $modalPath;
              if(class_exists($modalName))
              {

                  $this->_modal = new $modalName();
              }
          }

      }



      /**
       * @param $name
       * @param $params
       * @return mixed
       *
       *   Controllerda çalıştırılan ifade db>modol>asset sıralaması ile çağırılır
       */
      public function __call($name,$params)
      {


          if(method_exists($this->_modal,$name)) return call_user_func_array(array($this->_modal,$name),$params);

          elseif( method_exists($this->_assets,$name)) return call_user_func_array(array($this->_assets,$name),$params);
      }


  }