<?php

use Myfc\MainController;
use Myfc\Facade\Event;
/**
 * Class index
 *
 *  ****************************
 *
 *  MyfcFramewok standart index contoller dosyası
 *
 *
 *  ****************************
 */
  class index extends MainController
  { 
      

     public function __construct()
     {
         
         parent::__construct();
   
         
     }
	 
	 public function getir($dil = "tr", $index = 0){
		
                $files = Event::fire('file.get.html', array($dil));
               
                $files = array_map(function($a) use ($dil){
                    
                    $a = str_replace("app/Views/files/$dil", "", $a);
                    $a = str_replace(".html", "", $a);
                    return mb_convert_case($a, MB_CASE_TITLE, 'UTF-8');
                    
                }, $files[0]);
              
               
		$fire = Event::fire('check.file', array($index,$dil));
                $content = 'İçerik Yok';
                if($fire[0]){  
                    $content = file_get_contents($fire[0]);
                }
                
                view('dokumantasyon', compact('content','files'), true);
		 
	 }
    


  }

?>