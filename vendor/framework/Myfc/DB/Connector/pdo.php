<?php
namespace Myfc\DB\Connector;

 use PDOException;
 use Exception;
/**
 *
 * @author vahitþerif
 *        
 */
class pdo 
{
    
    private $pdo;

    /**
     * 
     * Baþlatýcý Sýnýf
     * 
     * @param array $configs
     */
    function __construct( array $configs, $type )
    {
        
        extract($configs);
        
        try{
            
            
            $pdo = new \PDO("$type:host=$host;dbname=$dbname",$username,$password);
            $pdo->query("SET CHARSET $charset");
            
            $this->pdo = $pdo;
            
            
        }catch(PDOException $e)
        {
            
           throw new Exception($e->getMessage());
            
        }
        
    }
        
      
        
        public function __call( $name, $params)
        {
            
            return call_user_func_array(array($this->pdo,$name), $params);
            
            
        }
        
        
    }


?>