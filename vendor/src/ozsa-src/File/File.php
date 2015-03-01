<?php

 Class File{

     public $in;
     public static $handle;

     public static function includeFile($path)
     {
         if(!in_array($path,self::$handle))
         {
             return require $path;
         }
     }

      public static function lock($path)
      {
          self::$handle[] = $path;
      }

     public static function makeDir($path)
     {
         $path = str_replace("\\", "/", $path);
         $path = explode("/", $path);

         $rebuild = '';
         foreach($path AS $p) {

             // Check for Windows drive letter
             if(strstr($p, ":") != false) {
                 $rebuild = $p;
                 continue;
             }
             $rebuild .= "/$p";
             //echo "Checking: $rebuild\n";
             if(!is_dir($rebuild)) mkdir($rebuild);
         }
     }
     public static function listing($path) {
         $arr = array();
         if(is_dir($path)) {
             // Open the source directory to read in files
             $i = new DirectoryIterator($path);
             foreach($i as $f) {
                 if(!$f->isDot())
                     $arr[] = $f->getFilename();
             }
             return $arr;
         }
         return false;
     }
     public static function delete($src)
     {
         if (is_dir($src) && $src != "") {
             $result = self::Listing($src);

             // Bring maps to back
             // This is need otherwise some maps
             // can't be deleted
             $sort_result = array();
             foreach ($result as $item) {
                 if ($item['type'] == "file") {
                     array_unshift($sort_result, $item);
                 } else {
                     $sort_result[] = $item;
                 }
             }

             // Start deleting
             while (file_exists($src)) {
                 if (is_array($sort_result)) {
                     foreach ($sort_result as $item) {
                         if ($item['type'] == "file") {
                             @unlink($item['fullpath']);
                         } else {
                             @rmdir($item['fullpath']);
                         }
                     }
                 }
                 @rmdir($src);
             }
             return !file_exists($src);
         }
     }
     public static function getContent($path)
     {
         return file_get_contents($path);
     }
     public static function setContent($path,$content)
     {
         return file_put_contents($path,$content);
     }
     public static function chechk($path)
     {
         if(file_exists($path)) return true;
     }
     public static function scanType($path,$type)
     {
     $pattern = $path.".{$type}";
     return glob($pattern,GLOB_BRACE);
     }
     public function in($path)
     {
         if($this->in) $this->in .= "/".$path;else $this->in .= $path;
         return $this;
     }
     public function scan($path,$type = GLOB_NOSORT,$realpath = false)
     {
         $pattern = glob($path,$type);
         if($realpath) {
            $pattern = array_map('realpath',$pattern);
         }
     }
     public static function reName($oldname,$newname)
     {
          rename($oldname,$newname);
     }
     public static function deleteAllDir($dir)
     {
         $scan = self::scan($dir,GLOB_BRACE,true);
         foreach($scan as $key)
         {
             unlink($key);
         }
     }

 }