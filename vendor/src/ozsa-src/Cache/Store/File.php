<?php

   namespace Cache\Store;


   class File{

       public $filepath;

       private  $cache;
       private  $cacheType;
       public   $cacheFiles;
       public   $newCacheFile;
       public   $cacheFile;

       public function __construct( $filepath )
       {

           $this->cacheFile = $filepath;

       }
       public  function get($name)
       {

           $cacheFiles = $this->cacheFiles;

           $file =$cacheFiles[$name]['newFilePath'];
           $time = $cacheFiles[$name]['times'];
           if(file_exists($file)){
               $value = file_get_contents($file);
               $value = json_decode($value);

               if( is_object($value) )
               {



                   return $value->content;

               }
           }else{return false;}
       }
       
       public   function  check($name){
           $file = $this->cacheFiles[$name]['newFilePath'];
           return (file_exists($file)) ? true:false;
       }
       public   function set($name,$value,$times = 3600)
       {
           $newCacheFile = $this->cacheFile."/".md5($name).".json";

           if(!file_exists($newCacheFile))
           {
               $this->cacheFiles[$name] = array(
                   'name' => $name,
                   'newFilePath' => $newCacheFile,
                   'times' => $times,
               );

               if(!is_array($value)) {
                   $values = array();
                   $values['content'] = $value;

                   @$values['times'] = $times;
               }



               $value = json_encode($values);

               touch($newCacheFile,time()+$times);
               $ac = fopen($newCacheFile,"w");
               $yaz = fwrite($ac,$value);
               fclose($ac);
           }else{
               unlink($newCacheFile);
               $this->set($name,$value,$times);
           }

       }
       public   function delete($name)
       {
           $file = $this->cacheFiles[$name]['newFilePath'];
           if(file_exists($file))
           {
               unlink($file);
               return true;
           }else{
               return false;
           }

       }
       public   function flush()
       {
           $filePath = $this->cacheFile;

           if(is_dir($filePath))
           {
               $ara = scandir($filePath);

               foreach ($ara as $key ) {
                   if( $key != "." && $key != ".." && !is_dir($filePath))
                   {
                       unlink($filePath);
                   }
               }
           }

       }

   }