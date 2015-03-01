<?php

 namespace Adapter;

 /**
  * Interface AdapterInterface
  * @package Adapter
  */

 interface AdapterInterface
 {

     /**
      * @return mixed
      *
      *  Sınıfın Görünecek adını döndürür
      */

      public function getName();

     /**
      * @return mixed
      *  Adapter tarafından sınıfın başlatılmasını sağlar
      */
      public function boot();

 }