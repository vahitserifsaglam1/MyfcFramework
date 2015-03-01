<?php

 namespace Session\Store;

 class Php{

     public  function set($name,$value)
     {
         $_SESSION[$name] = $value;
     }
     public  function flush()
     {
         foreach($_SESSION as $key => $value)
         {
             unset($_SESSION[$key]);
         }
     }
     public  function get($name)
     {
         if(isset($_SESSION[$name])) return $_SESSION[$name];else return false;
     }
     public  function delete($name)
     {
         if(isset($_SESSION[$name])) unset($_SESSION[$name]);else error::newError(" $name diye bir session bulunamadı ");
     }


     public static function __callStatic($name, $params)
     {

         $s = new static();

         return call_user_func_array(array($s,$name),$params);

     }

 }