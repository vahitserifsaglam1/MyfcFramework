<?php

  namespace Myfc\Cache\Connector;


  class Apc
  {
      public $caches;

      public function get($name)
      {
          return apc_fetch($name);
      }

      public function set($name,$value, $time = 0)
      {
          $this->caches[$name] = $value;
          return apc_store($name,$value,$time);
      }
      public function delete($name)
      {
          if(isset($this->caches[$name])) unset($this->caches[$name]);
          return apc_delete($name);
      }
      public function flush()
      {
          if(isset($this->caches))
          {
              foreach($this->caches as $key)
              {
                  apc_delete($key);
              }
          }
      }
  }