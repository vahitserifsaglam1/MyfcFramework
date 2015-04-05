<?php

   namespace Myfc\View;
   
   use Myfc\View;


  class Loader

  {


      public function load($path,array $params = array(),$rendefiles = '', array $templateArray = array(),$autoload = true)
      {

          return View::render($path,$params,$rendefiles,$templateArray, $autoload);

      }

  }