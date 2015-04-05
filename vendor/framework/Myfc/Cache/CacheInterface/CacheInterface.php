<?php


 namespace Myfc\Cache;

 interface CacheInterface{

     public function get($name);
     public function set($name, $value, $time);
     public function delete($name);
     public function flush();
     public function check();

 }