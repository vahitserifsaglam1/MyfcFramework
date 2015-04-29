<?php


namespace Myfc\Config;

/**
 *
 * @author vahitşerif
 */
interface ConfigInterface {
    
    /**
     * Yeni bir instance oluşturur
     */
    
    public static function boot();
            
            /**
             * Veriyi Döndürür
             * @param string $name
             * @param string $config
             */
    public static function get($name,$config);
    
   
    public static function set($name,$configs,$value);
    
}
