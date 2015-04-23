<?php



namespace Myfc\Template\MyfcTemplate;
use Myfc\File;
use Myfc\Template\MyfcTemplate\Exceptions\FileExceptions\FileIsNotExists;
use Myfc\Template\MyfcTemplate\Exceptions\FileExceptions\FileIsNotReadable;
/**
 * MyfcTemplateLoader
 *
 * @author vahitşerif
 */
class MyfcTemplateLoader {
    private $filesystem;
    private $filetype;
    /**
     * başlatıcı fonksiyonu
     * @param File $filesystem
     * @param string $filetype
     */
    public function __construct(File $filesystem, $filetype = '') {
        $this->filesystem = $filesystem;
        $this->filetype = $filetype;
        
   
    }
    
 
    /**
     * 
     * @param string $path
     * @return \Myfc\Template\MyfcTemplate\MyfcTemplateLoader
     */
    public function setTemplatePath($path = ''){
    
        $this->filesystem->in($path);
        return $this;
        
    }
    
    /**
     * 
     * @return string
     */
    
    public function getTemplatePath(){
        
        return $this->filesystem->getIniPath();
        
    }
    
    /**
     * 
     * @param string $path
     * @return string
     */
    
    private function generateFilePath($path = ''){
        
        if(strstr($path, ".")){
            
            $path = str_replace(".", "/", $path);
            
        }
        
        return $path.$this->filetype;
        
    }
    
    public function load($file){
        
      
        $path = $this->generateFilePath($file);
      
        if($this->filesystem->exists($path)){
            
            if(is_readable($this->filesystem->inPath($path))){
                
                return $this->filesystem->read($path);
                
            }else{
                
               throw new FileIsNotReadable(sprintf("%s dosyası okunabilir bir dosya değil",$this->filesystem->inPath($path)));
                
            }
            
        }else{
            
            
            throw new FileIsNotExists(sprintf("%s dosyası bulunamadı", $this->filesystem->inPath($path)));
            
        }
        
    }
    
    
    
}
