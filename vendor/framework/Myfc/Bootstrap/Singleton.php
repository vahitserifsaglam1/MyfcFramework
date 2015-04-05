<?php
 namespace Myfc;

 use ReflectionClass;
/**
 *
 * @author Vahit Şerif Sağlam
 *        
 */
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
    
    public static function make( $class, $parametres = array() )
    {

        $parametres = (!is_array($parametres)) ?  static::unsetFirstParametres(func_get_args()):$parametres;
        
        if( !isset( static::$classes[$class] ) )
        {

            self::$classes[$class] = (new ReflectionClass($class))->newInstanceArgs($parametres);
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
    
    public static function unsetFirstParametres( array $array = array() )
    {
        
          unset($array[0]);

          return  $array;
        
    }
    
    
}

?>