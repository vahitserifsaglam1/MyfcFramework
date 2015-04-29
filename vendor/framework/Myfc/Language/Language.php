<?php
namespace Myfc;

 use Myfc\File;
/**
 *
 * @author vahit�erif
 *
 * Myfc Framework Language sınıfı
 */
class Language
{

    private $path = LANGUAGE_PATH;
    
    private $files;
    
    private $file;
    /**
     */
    
    public function setPath($path){
        
        $this->path = $path;
        return $this;
        
    }
    
    public function getPath(){
        
        return $this->path;
        
    }
    
    public function __construct()
    {
        
        $this->file = File::boot();
        
        $this->files = $this->file->in($this->getPath())->getType('.php');
        
    }
    
    /**
     * Kurulumu yapar
     */

    
    
    public function rende($dil,$name)
    {
        
        $path = $dil.DIRECTORY_SEPARATOR.$name.".php";

       
        if($this->file->exists($path))
        {
            
          
            return $this->file->inc($path);
            
        }
        
    }
    
    /**
     * Interface zorunlu fonksiyonu
     * @return strıng
     */
    
    public function getName()
    {
        
        return "language";
        
    }
}

?>