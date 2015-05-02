<?php

namespace Myfc\Helpers;

/**
 * Description of ConfigBag
 *
 * @author vahitşerif
 */
trait ConfigBag {
    
    private $configs;
    
    /**
     * Ayarları döndürür
     * @return type
     */
    
    public function getConfigs(){
        
        return $this->configs;
        
    }
    
    public function setConfigs( array $configs = [] ){
        
        $this->configs = $configs;
        return $this;
        
    }
    
    /**
     * Seçilen ayarı döndürür
     * @param type $name
     * @return type
     */
    public function getConfig($name){
        
        return (isset($this->configs[$name])) ? $this->configs[$name]:false;
        
    }
    
    /**
     * Ayar ataması yapar
     * @param type $name
     * @param type $value
     * @return type
     */
    public function setConfig($name, $value){
        
        $this->configs[$name] = $value;
        return $this;
        
    }
    
}
