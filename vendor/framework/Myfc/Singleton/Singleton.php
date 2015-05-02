<?php
/**
 *
 * @author Vahit Şerif Sağlam
 *        
 */
 namespace Myfc;
 use ReflectionClass;
 class Singleton
 {
 
     protected static $classes;
     
     protected static $classCount;
     
    /**
     *  
     *   Ba�lat�c� fonksiyon
     * 
     */
    public function __construct()
    {
        
         
        
    }
    
    public static function make( $class, $parametres = [] )
    {
        $parametres = (!is_array($parametres)) ?  static::unsetFirstParametres(func_get_args()):$parametres;
        
        if( !isset( static::$classes[$class] ) )
        {
            
            $classs = new ReflectionClass($class);
            //  reflection sınıfımızı oluşturduk
            self::$classes[$class] = $classs->newInstanceArgs($parametres);
            self::$classCount++;    
        }
        return static::$classes[$class];
        
    }
    
    public static function returnClassCount()
    {
        
        return static::$classCount;
        
    }
    
    
    /**
     * S�n�f�n ilk parametresini silmekde kullan�lacak
     * @param array $array
     */
    
    public static function unsetFirstParametres( array $array = [] )
    {
        
          unset($array[0]);

          return  $array;
        
    }
    
    
 }
