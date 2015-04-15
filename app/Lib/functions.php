<?php

use Myfc\Facade\View;
use Myfc\Facade\CSRF;

/*
 * View oluşturma fonksiyonu
 */

if(!function_exists('view')){

    /**
     * 
     * @param type $dir
     * @param array $params
     * @param type $autoload
     * @return type
     */
    function view($dir = '',array $params = array(), $autoload = false){
        
        return View::make($dir, $params, $autoload)->execute();
        
    }
    
}


if(!function_exists('compact')){
    
    /**
     * Girilen string değere atanmış bir değişen olup olmadığına bakar
     * Eğer varsa deperi yoksa false döner
     * @param string $paramName
     * @return array|boolean
     */
    function compact($paramName = ''){
         $variables = get_defined_vars();
        if(func_num_args() == 1){
              if(isset($variables[$paramName])){
           
                   $return = [$paramName => $variables[$paramName]];
            
        }else{
            
            return [$paramName => null];
            
        }}else{
            
            foreach(func_get_args() as $param){
                
                 if(isset($variables[$param])){
           
                   $return[$param] = $variables[$param];
            
        }else{
            
                   $return[$param] = null;
            
        }
                
            }
            
        }
       
        return $return;
      
        
    }
    
    
}


// crsf token

 if(!function_exists('csrf_active')){
     
     /**
      * Csrf sınıfıyla iletişime geçerek eşsiz bir token oluşturur
      * @return string
      */
     function csrf_active(){
         
         return CSRF::active();
         
     }
     
 }
 
  if(!function_exists('csrf_check')){
     
     /**
      * Csrf sınıfıyla iletişime geçerek eşsiz bir token oluşturur
      * @return string
      */
     function csrf_check(){
         
         return CSRF::check();
         
     }
     
 }



