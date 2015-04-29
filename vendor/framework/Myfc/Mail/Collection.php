<?php


namespace Myfc\Mail;

class Collection {
    
    private $collection;
    
    
    public function __construct($array = array()) {
        $this->collection = $array();
    }
    
    
    public function __set($name,$value){
        
        $this->collection[$name] = $value;
        return $this;
        
    }
    public function __get($name) {
        if(isset($this->collection[$name])){
            
            return $this->collection[$name];
        }else{
            
            return null;
            
        }
        
    }
    
    public function setCollections($array){
        
        $this->collection = $array();
        
    }
    
    public function getCollections(){
        
        return $this->collection;
        
    }
    
}
