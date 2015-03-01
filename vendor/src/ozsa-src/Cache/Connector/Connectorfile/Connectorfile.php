<?php

  namespace Cache\Connector;


  class Connectorfile
  {

      public $filepath;

      public function __construct($configs)
      {

           $filepath = $configs['path'];

           if(file_exists($filepath))
           {

               return true;

           }else{

                $filesystem = \Filesystem::boot('Local');

                $filesystem ->mkdir($filepath);
           }

          $this->filepath = $filepath;

      }

      public function getName()
      {

          return "file";

      }

      public function boot()
      {

          return new \Cache\Store\File( $this->filepath );

      }

  }