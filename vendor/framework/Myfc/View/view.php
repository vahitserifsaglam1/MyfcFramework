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
    
    private function languageInstall($lang){

         $rende = array();
         
         foreach($lang as $l => $name){
             
            $rende[] = $this->lang->rende($lang, $name);
             
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
      
        
        $path = APP_PATH.'Views/'.$path.self::TWIG_EXTENSION.self::FILE_EXTENSION;
        
    
        
        if(file_exists($path)){
             
            return [
                'filepath' => $path,
                'templateFilePath' => $templateFilePath,
                'templateFile' => $templateFile.self::TWIG_EXTENSION.self::FILE_EXTENSION ];
            
        }else{
            
            return false;
            
        }
        
    }
    
    /**
     * İçerik oluşturma fonksiyonu
     * @param type $path
     * @param type $params
     */

    public function make($path = '', $params = array(),$autoload = true){
        
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
        
         $this->template->execute($this->file);
        
        
    }
    
}
