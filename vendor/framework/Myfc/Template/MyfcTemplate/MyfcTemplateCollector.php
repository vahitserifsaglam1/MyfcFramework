<?php



namespace Myfc\Template\MyfcTemplate;

/**
 * Description of MyfcTemplateCollector
 *
 * @author vahitşerif
 */
class MyfcTemplateCollector {
   
    private $collection = array();
    
    public function __construct() {
        
    }
    
    /**
     * Sınıfa collection eklemesi yapar
     * @param type $params
     * @param type $vall
     * @return \Myfc\Template\MyfcTemplate\MyfcTemplateCollector
     */
    public function addCollection($params, $vall = null){
        
        if(is_null($vall) && is_array($params)){
            $params = array_map(function($a){
               
                if(is_array($a)){
                    
                    return (object) $a;
                    
                }else{
                    
                    return $a;
                    
                }
                
            },$params);
            
            $this->collection = array_merge($this->collection, $params);
            
        }elseif(!is_null($vall) && !is_array($params)){
            
           $this->collection[$params] = $vall;
            
        }
        
        
        return $this;
        
    }
    
    /**
     * Collectionları döndürür
     * @return array
     */
    public function getCollections(){
        
        return $this->collection;
        
    }
    
    /**
     * 
     * @param string $name
     * @return mixed
     */
    
    public function get($name = ''){
        
        if(isset($this->collection[$name])){
            
             return $this->collection[$name];
            
        }
        
    }
    
    /**
     * 
     * @param type $name
     */
    public function delete($name){
        
        if(isset($this->collection[$name])){
            
            unset($this->collection[$name]);
            
        }
        
    }


    public function flush(){
        
        foreach($this->collection as $key => $value){
            
            unset($this->collection[$key]);
            
        }
        
    }
    
}
