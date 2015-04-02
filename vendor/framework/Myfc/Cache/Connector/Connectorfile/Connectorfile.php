<?php

  namespace Myfc\Cache\Connector;

   use Myfc\Filesystem;

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

                $filesystem = Filesystem::boot('Local');

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

          return new \Myfc\Cache\Store\File( $this->filepath );

      }

  }