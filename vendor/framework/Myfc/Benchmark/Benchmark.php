<?php

namespace Ozsa;

 class Benchmark
 {
      public $memoryusage;
      public $microtime;

      public function micro($name)
      {
          $this->microtime[$name] = microtime();
          return $this;
      }
      public function elapsed_time($baslangic,$son,$decimals =4 )
      {

          list($start1,$start2) = explode(' ',$this->microtime[$baslangic]);
          list($finish2,$finish3) = explode(' ',$this->microtime[$son]);
          $start = $start1 + $start2;
          $finish = $finish2 + $finish3;
          return number_format(($finish-$start),$decimals);
      }
     public function memory($name)
     {
         $this->memoryusage[$name] = memory_get_usage(true);
         return $this;
     }
     public function used_memory($start,$finish)
     {
         @$start = $this->memoryusage[$start];
         @$finish = $this->memoryusage[$finish];
         return (isset($start)&& isset($finish)) ? $finish-$start:false;
     }
     public function included_files()
     {
         if(function_exists('get_included_files')) return get_included_files();
     }
 }