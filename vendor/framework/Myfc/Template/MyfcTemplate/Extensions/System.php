<?php


namespace Myfc\Template\MyfcTemplate\Extensions;
use Myfc\Support\Str;
use Myfc\Template\MyfcTemplate\Interfaces\MyfcTemplateExtensionInterface;
/**
 * Description of System
 *
 * @author vahitşerif
 */
class System implements MyfcTemplateExtensionInterface {
    
    public function getname(){
        
        return "System";
        
    }
    
    public function boot(){
        
        //
        
    }
    
    public function get($name){
        
        return $name;
        
    }

        /**
     * Uzunluğu döndürür
     * @param type $param
     * @return type
     */
    public function length($param){
        
        return Str::length($param);
        
    }
    
    /**
     * Girilen metni küçültür
     * @param type $value
     * @return type
     */
    public function lower($value){
        
        return Str::lower($value);
        
    
    }
    
    /**
     * Girilen metni büyütür
     * @param type $value
     * @return type
     */
    public function upper($value){
        
        return Str::upper($value);
        
    }
    
    /**
     * Başharfleri büyütür sadece
     * @param type $value
     * @return type
     */
    public function title($value){
        
        return Str::title($value);
        
    }
    
    public function escape($value){
        
        return filter_var($value, FILTER_SANITIZE_STRING);
        
    }
    
}
