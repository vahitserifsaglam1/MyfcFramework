<?php


namespace Myfc\Mail;
use Myfc\Helpers\ConfigBag;
class Collection {
    
    use ConfigBag;
    
    private $collection;
    
    
    public function __construct($array = []) {
        $this->setConfigs($array);
    }
    
    
    public function __set($name,$value){
        
        $this->setConfig($name,$value);
        return $this;
        
    }
    public function __get($name) {
        
        return $this->getConfig($name);
        
    }
    
    public function setCollections($array){
        
        $this->setConfigs($array);
        
    }
    
    public function getCollections(){
        
        return $this->getConfigs();
        
    }
    
}
