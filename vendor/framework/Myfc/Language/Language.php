<?php
namespace Myfc;

 use Myfc\File;
/**
 *
 * @author vahitþerif
 *        
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
        
        $path = LANGUAGE_PATH.'/'.$dil.'/'.$name.'.php';
        
        if($this->file->exists($path))
        {
            
            return require $path;
            
        }
        
    }
}

?>