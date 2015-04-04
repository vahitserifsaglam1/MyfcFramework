<?php

 namespace Myfc\Cache\Store;


 class Predis
 {

     public $predis;

     public function __construct( $predis )
     {

         $this->predis = $predis;

     }

     public function __call( $name,$params )
     {

         return call_user_func_array(array($this->predis,$name),$params);

     }



 }