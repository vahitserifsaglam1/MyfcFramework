<?php

/**
 * Class index
 *
 *  ****************************
 *
 *  OzsaFramework standart index contoller dosyası
 *
 *
 *  ****************************
 */
  class index extends MainController
  {
       public function __construct()
       {
          parent::__construct();
           $this->_view->load('index',true);
       }



  }

?>