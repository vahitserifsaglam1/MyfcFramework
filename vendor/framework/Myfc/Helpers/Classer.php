<?php

namespace Myfc\Helpers;
use ReflectionClass;
/**
 *
 * @author vahitşerif
 */
trait Classer {
    
    
    /**
     * Sınıf oluşturma fonksiyonu
     * @param type $className
     * @return ReflectionClass
     */
    
    public function  generateClass($className = '', $args = []){
        
        // sınıf oluşturuldu ve paremetreler atandı
        $class = new ReflectionClass($className);
        return  $class->newInstanceArgs($args);
        
    }
    
}
