<?php
namespace Myfc;

/*
 *
 *   MyfcFramework MainController Sınıfı
 * 
 *   SuperSınıfdır
 *  
 */

 use Myfc\Singleton;
/**
 *
 * @author vahitşerf
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
     * Ba�latma Fonksiyon
     */
    
    public function __construct()
    {
        
      $this->collection['assets'] = Singleton::make('Myfc\Assets');
        
    }
    
    /**
     * Modal �a��rma fonksiyonu
     *  
     *    $modal de�i�kenine atanan isimde bir modal arar ve bulursa �a��r�r
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
     * Dinamik olarak de�er �a�r�lmas�
     * 
     *   $name de�i�kenin ald��� de�er $collection i�inde varsa d�nd�r�l�r
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
     *  Ca�r�lan method s�n�fta yoksa tetiklenir
     * 
     * @param string $name
     * @param array $parametres
     * @return mixed
     */
    
    public function __call($name = '',array $parametres = [])
    {
        $thi = $this;
        array_map(function($a) use ($name,$parametres,$thi){
             if(method_exists($thi->collection[$a],$name) || is_callable([$thi->collection[$a],$name])){
                 
                 return call_user_func_array([$thi->collection[$a],$name],$parametres);
                 
             }
                
        }
            , $this->collection); 
        
        
    }
}

?>