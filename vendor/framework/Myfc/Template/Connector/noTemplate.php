<?php



namespace Myfc\Template\Connector;
use Myfc\File;
/**
 * Myfc framework template sınıfı php connector sınıfı
 * Template engine kullanmak istemeyen arkadaşlar düz php kullanması için geliştiriliyor
 *
 * @author vahitşerif
 */
class noTemplate {
   
    private $parametres;
    private $file;
    
    public function __construct(){
        
        $this->file = new File();
        $this->file->in(VIEW_PATH);
        
    }
    
    public function useTemplateParametres(array $parametres){
        
        $this->parametres = $parametres;
        return $this;
        
    }
    
    public function execute($filename){
        
    
        $parametres = $this->parametres;
        
        $filename = $filename.".php";
    
        if($this->file->exists($filename)){
            
            $this->file->inc($filename,$parametres);
            
        }else{
            
            return false;
            
        }
        
    }
    
}
