<?php


namespace Myfc\Template\MyfcTemplate;
use Myfc\Template\MyfcTemplate\Interfaces\MyfcTemplateExtensionInterface;
use Myfc\Template\MyfcTemplate\Exceptions\InterfaceExceptions\MyfcTemplateExtensionInterfaceException;
use Myfc\Template\MyfcTemplate\Extensions\System;

/**
 *  MyfcTemplateExtensionManager
 *
 * @author vahitşerif
 */
class MyfcTemplateExtensionManager {
  
    private $extensions;
    
    public function __construct() {
        
        $this->addExtension( new System());
        
    }
    
    /**
     * Sınıfa eklenti ekler
     * @param \Myfc\Template\MyfcTemplate\extensionObject $extensionObject
     * @return boolean
     */
    public function addExtension($extensionObject){
        
        if(!is_object($extensionObject)){
            
            $extensionObject = new $extensionObject;
            
        }
        
        if($this->extensionInterfaceChecker($extensionObject)){
            
            $extensionObject->boot();
            $this->extensions[$extensionObject->getName()] = $extensionObject;
            
            
        }else{
            
            throw new MyfcTemplateExtensionInterfaceException(sprintf(" %s sınıfınız bir MyfcTemplateExtensionInterface e ait değil", get_class($extensionObject)));
            
        }
        
        
        
        return true;
        
    }
    
    /**
     * 
     * @param string $name
     * @return MyfcTemplateExtensionInterface
     */
    
    public function getExtension($name){
        
        if(isset($this->extensions[$name])){
            
            return $this->extensions[$name];
            
            
        }
        
    }
    
    /**
     * eklentilerin tamamını döndürür
     * @return MyfcTemplateExtensionInterface
     */
    public function getExtensions(){
        
        return $this->extensions;
        
    }

        private function extensionInterfaceChecker($extensionObject){
        
        if($extensionObject instanceof MyfcTemplateExtensionInterface){
            
            return true;
            
        }
        
    }
    
}
