<?php

 namespace Myfc\Session;
 
  /**
   * 
   * Extend lerde kullanýlmak üzere Interfaceler 
   * @author vahitþerif
   *
   */
 
 interface SessionInterface{
     
     
     public function flush();
     
     public function get($name);
     
     public function set($name, $value);
     
     public function delete($name);
     
     public function check();
     
     
 }