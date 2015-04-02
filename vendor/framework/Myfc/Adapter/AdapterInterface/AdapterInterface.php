<?php

 namespace Myfc\Adapter;

 /**
  * Interface AdapterInterface
  * @package Adapter
  */

 interface AdapterInterface
 {

     /**
      * @return mixed
      *
      *  Sýnýfýn görünecek ve çaðrýlacak adý
      */

      public function getName();

     /**
      * @return mixed
      *  Adaptere eklenen sýnýflarýn baþlatýlmasýný saðlar
      */
      public function boot();

 }