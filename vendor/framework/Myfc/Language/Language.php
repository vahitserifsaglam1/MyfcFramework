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

    private $path;
    
    private $files;
    
    private $file;
    /**
     */
    public function __construct()
    {
        

        $this->path = LANGUAGE_PATH;
        
        $this->file = File::boot();
        
        $this->files = $this->file->in($this->path)->getType('.php');
        
    }
    
    /**
     * Kurulumu yapar
     */

    
    
    public function rende($dil,$name)
    {
        
        $path = $dil."/".$name.".php";

       
        if($this->file->exists($path))
        {
            
          
            return $this->file->inc($path);
            
        }
        
    }
    
    public function getName()
    {
        
        return "language";
        
    }
}

?>