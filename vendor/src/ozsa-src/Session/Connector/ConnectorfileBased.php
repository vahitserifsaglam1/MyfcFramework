<?php

   namespace Session\Connector;


   class ConnectorfileBased{

       public $filesystem;

       public $options;

       public function __construct( $options )
       {

           if( class_exists('Filesystem',false) )
           {

               $this->filesystem = \Filesystem::boot('Local');
               $this->options = $options;

           }else{

               throw new \Exception(' Filesystem sınıfınız bulunamadı ');

           }

       }
       public function boot()
       {

           return new \Session\Store\File($this->filesystem,$this->options);

       }


   }