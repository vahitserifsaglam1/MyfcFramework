<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Myfc;

use Myfc\Singleton;
use Myfc\Template\Engine;
use Myfc\Language;

/**
 * Description of Views
 *
 * @author vahitşerif
 */
class View {
    

    private $template;
    private $templateVariables = array();
    private $file;
    private $autoload;
    private $lang;
    
    public function __construct() {
   
         $this->lang = new Language();
         $this->template = new Engine();
    }
    
    public function templateConnectWith($driverName){
        
        $this->template->connect($driverName);
        
    }


    private function languageInstall($lang){

         $rende = array();
         
         foreach($lang as $l => $name){
             
          
            $rende  = array_merge($rende,$this->lang->rende($l, $name));
             
         }
         
         return $rende;
        
    }
    
    /**
     * Template engine e istediği verileri atıyoruz
     * @param type $file
     * @param array $values
     */
    private function setTemplateVariables(array $values = array()){
        
        $this->template->useTemplateParametres($values);
        
    }

 
   
    
    
    /**
     * Dosya yolunu oluşturduk
     * @param string $path
     * @return boolean|string
     */
    private function createFilePath($path = ''){
        
        if(strstr($path,".")){
            
            $path = str_replace(".", "/", $path);
            
        }
        
        $explode = explode("/", $path);
        
        // Tempalte dosyası
        
        $templateFile = end($explode);
        
        //
        
        $search = array_search($templateFile, $explode);
        
        
        if($search > 0){
            
              $key = $search-1;
              $templateFilePath = $explode[$key];
            
        }else{
            
            $templateFilePath = '';
            
        }
      
        
    return [
                'filepath' => $path,
                'templateFilePath' => $templateFilePath,
                'templateFile' => $templateFile  ];
            
      
        
    }
    
    /**
     * View dosyasının temelini hazırlar, çıktıyı tamponlamak için
     * Execute() fonksiyonu çağrılamlıdır
     * 
     * @param string $path dosyanın adı
     * @param array $params gönderilecek parametreler
     * @param boolean $autoload 
     * @return $this
     */

    public function make($path = '', $params = array(),$autoload = true){
       
         $this->autoload = $autoload;
         $num = func_num_args();
            
         if(isset($params['LANGUAGE'])){
             
             $lang = $params['LANGUAGE'];
             
             unset($params['LANGUAGE']);
             
             $languageParams = $this->languageInstall($lang);

             $params = array_merge($params, $languageParams);
             
       
         }
    
          $this->template->useTemplateParametres($params);

          $this->file = $path;
          
          return $this;
        
    }
    
    /**
     * 
     * @param array $variables
     * @return \Myfc\Views
     */
    
    public function with(array $variables = array()){
        
        $this->template->useTemplateParametres($variables);
        return $this;
    }
    
    /**
     * Çıktıyı gönderir
     */
    
    public function execute(){
         
 
          if($this->autoload){
              
              $this->template->execute("header");
              
          }
         $this->template->execute($this->file);
         
         if($this->autoload){
             
             $this->template->execute("footer");
             
         }
        
        
    }
    
}
