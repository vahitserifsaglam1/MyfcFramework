<?php

namespace Myfc;

 class Benchmark
 {
      public $memoryusage;
      public $microtime;

      /**
       * Mikrotime ölçer
       * @param unknown $name
       * @return \Myfc\Benchmark
       */
      
      public function micro($name)
      {
          $this->microtime[$name] = microtime();
          return $this;
      }
      
      /**
       * zaman Farkýný ölçer
       * @param unknown $baslangic
       * @param unknown $son
       * @param number $decimals
       * @return string
       */
      public function elapsed_time($baslangic,$son,$decimals =4 )
      {

          list($start1,$start2) = explode(' ',$this->microtime[$baslangic]);
          list($finish2,$finish3) = explode(' ',$this->microtime[$son]);
          $start = $start1 + $start2;
          $finish = $finish2 + $finish3;
          return number_format(($finish-$start),$decimals);
      }
      
      /**
       * Ram kullanýmýný ölçer
       * @param unknown $name
       * @return \Myfc\Benchmark
       */
     public function memory($name)
     {
         $this->memoryusage[$name] = memory_get_usage(true);
         return $this;
     }
     
     /**
      * Ram kullanýmýný karþýlaþtýrýr
      * @param unknown $start
      * @param unknown $finish
      * @return Ambigous <number, boolean>
      */
     public function used_memory($start,$finish)
     {
         @$start = $this->memoryusage[$start];
         @$finish = $this->memoryusage[$finish];
         return (isset($start)&& isset($finish)) ? $finish-$start:false;
     }
     
     /**
      * Ýnclude edilen dosyalarý getirir
      * @return multitype:
      */
     public function included_files()
     {
         if(function_exists('get_included_files')) return get_included_files();
     }
 }