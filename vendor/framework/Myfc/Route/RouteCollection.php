<?php

/*
 * 
 *  Route olaylarının toplanacağı sınıf
 * 
 */

namespace Myfc\Route;

/**
 *  RouteCollection
 *
 * @author vahitşerif
 */
class RouteCollection {
    
    private $collection = array(
        
        'GROUP' => array(),
        'GET'   => array(),
        'PUT'   => array(),
        'DELETE'=> array(),
        'WHEN'  => array()
         
    );
    
    /**
     * atanan whereler tutulur
     * @var type 
     */
    private $where = array();
    
    /**
     * Atanan filter lar tutunur
     * @var type 
     */
    private $filter;
    
    /**
     * Collection ataması yapar
     * @param string $type
     * @param mixed $patternAndCallback
     * @return null
     */
    
    private function setCollection($type, $patternAndCallback){
        
     
        $this->collection[$type][] = $patternAndCallback;
    
        return null;
        
    }
    
    /**
     * Collectionları döndürür
     * @return array
     */
    public function getCollection(){
        
        return $this->collection;
        
    }
    /**
     * Where ataması yapar
     * @param array $where
     */
    private function setWhere(array $where) {
        
        $this->where = $where;
        
    }
    
    /**
     * Get değerlerini döndürür
     * @return array
     */
    public function getWhere(){
        
        return $this->where;
        
    }
    
    /**
     * Filterları döndürür
     * @return type
     */
    public function getFilters(){
        
        return $this->filter;
        
    }


    /*
     * Filter eklemesi yapılır
     * 
     */
    private function addFilter($name,$callback){
        
        $this->filter[$name] = $callback;
        
    }

    /**
     * Get toplaması yapar
     * @param string $pattern
     * @param mixed $callback
     * @return \Myfc\Route\RouteCollection
     */

    public function get($pattern, $callback){
        
        $this->setCollection('GET', func_get_args());
        return $this;
        
    }
    
     /**
     * Post toplaması yapar
     * @param string $pattern
     * @param mixed $callback
     * @return \Myfc\Route\RouteCollection
     */

    public function post($pattern, $callback){
        
        $this->setCollection('POST',func_get_args());
        return $this;
        
    }
    
     /**
     * Delete toplaması yapar
     * @param string $pattern
     * @param mixed $callback
     * @return \Myfc\Route\RouteCollection
     */

    public function delete($pattern, $callback){
        
        $this->setCollection('DELETE', func_get_args());
        return $this;
        
    }
    
     /**
     * PUT toplaması yapar
     * @param string $pattern
     * @param mixed $callback
     * @return \Myfc\Route\RouteCollection
     */

    public function put($pattern, $callback){
        
        $this->setCollection('PUT', func_get_args());
        return $this;
        
    }
    
     /**
     * hepsinin toplaması yapar
     * @param string $pattern
     * @param mixed $callback
     * @return \Myfc\Route\RouteCollection
     */

    public function any($pattern, $callback){
        
        $this->get($pattern,$callback);
        $this->post($pattern, $callback);
        $this->put($pattern, $callback);
        $this->delete($pattern, $callback);
       
        return $this;
        
    }
    
    /**
     * Diziye atananların toplamasını yapar
     * @param array $types
     * @param string $pattern
     * @param mixed $callback
     * @return \Myfc\Route\RouteCollection
     */
    public function match(array $types = array(), $pattern,$callback){
        
        foreach($types as $key){
            
            $this->setCollection($key, array($pattern,$callback));
            
        }
        
        return $this;
        
    }
    
    /**
     * 
     * @param string $name
     * @param mixed $callback
     * @return \Myfc\Route\RouteCollection
     */
    
    public function filter($name, $callback){
        
        $this->addFilter($name,$callback);
        return $this;
        
    }
    
    /**
     * 
     * @param mixed $group
     * @param mixed $callback
     * @return \Myfc\Route\RouteCollection
     */
    public function group($group, $callback){
        
        $this->setCollection('GROUP', func_get_args());
        return $this;
        
    }
    
    /**
     * Where ataması yapar
     * @param type $name
     * @param type $pattern
     * @return \Myfc\Route\RouteCollection
     */
    
    public function where(array $where){
        
        $this->setWhere($where);
        return $this;
        
    }
    
    /**
     * Where ataması yapar, geri dönüş yapmaz
     * @param type $name
     * @param type $pattern
     */
    public function pattern($name, $pattern){
        
        $this->setWhere(func_get_args());
        return null;
        
    }
    /**
     * Return ataması yapar
     * @param string $pattern
     * @param mixed $callback
     * @return \Myfc\Route\RouteCollection
     */
    
    public function when($pattern, $callback){
        
        $this->setCollection('WHEN', func_get_args());
        return $this;
        
    }
    
   
}
