<?php
namespace Myfc;

 use Myfc\View\Loader;
 use Myfc\Singleton;
/**
 *
 * @author vahitþerif
 *        
 */

class MainController
{
    /**
     * 
     * @var array
     */

    private $collection = array();
    
    /**
     * Baþlatma Fonksiyon
     */
    
    public function __construct()
    {
        
      $this->collection['view'] = new Loader();
      
      $this->collection['assets'] = Singleton::make('Myfc\Assets');
        
    }
    
    /**
     * Modal Çaðýrma fonksiyonu
     *  
     *    $modal deðiþkenine atanan isimde bir modal arar ve bulursa çaðýrýr
     * 
     * @param string $modal
     */
    protected function modal($modal = '')
    {
        
        $path = APP_PATH."Modals/$modal.php";
        
        if(file_exists($path))
        {
            
            include $path;
            
            if(class_exists($modal))
            {
                
                $this->collection['modal'] = new $modal();
                
            }else{
                
                $this->collection['modal'] = null;
                
            }
            
        }
        
    }
    
    /**
     * Dinamik olarak deðer çaðrýlmasý
     * 
     *   $name deðiþkenin aldýðý deðer $collection içinde varsa döndürülür
     * 
     * @param string $name
     */
    public function __get($name = '')
    {
        
        if(isset($this->collection[$name]))
        {
            
            return $this->collection[$name];
            
        }
        
    }
    
    /**
     * 
     *  Caðrýlan method sýnýfta yoksa tetiklenir
     * 
     * @param string $name
     * @param array $parametres
     * @return mixed
     */
    
    public function __call($name = '',array $parametres)
    {
        
        array_map(function($a) use ($name,$parametres,$this){
             if(method_exists(array($this->collection[$a],$name)) || is_callable(array($this->collection[$a],$name))){
                 
                 return call_user_func_array(array($this->collection[$a],$name),$parametres);
                 
             }
                
        }
            , $this->collection);
        
        
    }
}

?>