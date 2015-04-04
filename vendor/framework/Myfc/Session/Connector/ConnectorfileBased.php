<?php

   namespace Myfc\Session\Connector;

    use Myfc\Filesystem;

   class ConnectorfileBased{

       public $filesystem;

       public $options;

       public function __construct( $options )
       {

           if( class_exists('Filesystem',false) )
           {

               $this->filesystem = Filesystem::boot('Local');
               $this->options = $options;

           }else{

               throw new \Exception(' Filesystem sýnýfý bulunamadÄ± ');

           }

       }
       public function boot()
       {

           return new \Myfc\Session\Store\File($this->filesystem,$this->options);

       }


   }