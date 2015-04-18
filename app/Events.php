<?php

 use Myfc\Facade\Event;
 use Myfc\Facade\File;

 Event::listen('check.file', function($file, $dil = "tr"){
     
    
     File::in(VIEW_PATH.'files/'.$dil.'/');
     
     $html = $file.".html";
     
     if(File::exists($html)){
         
         return File::inPath($html);
         
     }
 });

 Event::listen('file.get.html', function($dil = "tr"){
     
      File::in(VIEW_PATH.'files/'.$dil.'/');
      
      return File::getType('.html', null);
     
 });