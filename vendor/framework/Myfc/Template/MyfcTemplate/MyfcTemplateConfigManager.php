<?php

namespace Myfc\Template\MyfcTemplate;

/**
 *  MyfcTemplateConfigManager
 *
 * @author vahitşerif
 */
class MyfcTemplateConfigManager {
    
    private $configs = array();
    
    public function __construct(array $configs) {
       $this->setConfigs($configs) ;;
    }
    
    /**
     * 
     * @param array $configs
     * @return \Myfc\Template\MyfcTemplate\MyfcTemplateConfigManager
     */
    public function setConfigs(array $configs = array() ){
        
        $this->configs = $configs;
        return $this;
    }
    
    /**
     * 
     * @param string $name
     * @return mixed
     */
    public function get($name){
        
        if(isset($this->configs[$name])){
            
            return $this->configs[$name];
            
        }
        
    }
    
}
