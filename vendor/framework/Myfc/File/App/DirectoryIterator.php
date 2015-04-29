<?php
namespace Myfc\File\App;

/**
 *
 * @author vahit�erif
 * 
 *  S�n�flarda klas�rlerin i�eriklerini, klas�rlerini listelemek i�in kullan�lacak
 *        
 */
class DirectoryIterator
{
    /*
     *  @var $folder
     *  
     *  Se�ilen klas�r tutulur
     */
    public $folder;
    
    
    
    public $folders = [];
    
    public $files   = [];
    
    public $mixed   = [];
    
    /**
     * 
     * S�n�f� ba�lat�r e�er bir dosya giri�i yap�lm��sa onu listeler
     * @param null $folder
     */
   public function __construct( $folder = null )
    {
        
        if($folder !== null)
        {
            
           $this->read($folder);
           
        }
        
    }
    
    /**
     * 
     * @param string $folder
     * @return \Myfc\App\DirectoryIterator
     */
    public function read($folder = '')
    {
        
        if( !strstr($folder, "/"))
        {
        
            $folder =  $folder."/";
        
        
        }
        $this->folder = $folder;
        
        $this->readFolders();
        
        $this->readFiles();
        
        $this->mix();
        
        return $this;
        
    }
    
    /**
     * Klas�rdeki dosyalar� okur
     * @return string
     */
    public function readFiles()
    {
        
        $folder = $this->folder;
        
        
        $glob = glob($folder."*",GLOB_NOSORT);
        
        $glob = array_filter($glob,function($key){
            
            $key = realpath($key);
            
            if(!is_dir($key))
            {
                
                return $key;
                
            }
        });
        
        
        $this->files = $glob;
        
    }
    
    /**
     * Klas�rdeki klas�rleri okur
     */
    
    public function readFolders()
    {
        
        $folder = $this->folder;
        
        $glob = glob($folder."*",GLOB_ONLYDIR);
        
        $this->folders = $glob;
        
    }
    
    /**
     * Mixed 
     */
    public function mix()
    {
        
        $this->mixed = array_merge($this->files,$this->folders);
        
    }
    
    /**
     *  
     *   Dosyalar� D�nd�r�r
     * 
     * @return multitype:
     */
    public function getMixed()
    {
        
        return $this->mixed;
        
    }
    
    /**
     *  
     *   Klas�rleri D�nd�r�r
     * 
     * @return multitype:
     */
    public function getFolders()
    {
        
        return $this->folders;
        
    }
    
    /**
     *  
     *   T�m i�eri�i D�nd�r�r
     *  
     * @return multitype:
     */
    public function getFiles()
    {
        
        return $this->files;
        
    }
    
    /**
     * Listelemede noktalar� temizlek i�in kullan�lacak
     * @param string $realpath
     * @return boolean
     */
    public function isDot($realpath = '')
    {
        
      if($realpath == "." || $realpath == "..")
      {
          
          return true;
          
      }else{
          
          return false;
          
      }
        
    }
    
    /**
     * Girilen yoldaki i�eri�in dosya olup olmad���n� kontrol eder
     * @param unknown $file
     * @return boolean
     */
    
    public function isFile($file)
    {
        
        if(file_exists($file))
        {
            
            if(is_file($file))
            {
                
                return true;
                
            }
            
        }
        return false;
    }
    
    /**
     * Girilen yoldaki i�eri�in klas�r olup olmad���n� kontrol eder
     * @param unknown $folder
     * @return boolean
     */
    public function isFolder($folder)
    {
        
        if(file_exists($folder))
        {
            
            if(is_dir($folder))
            {
                
                return true;
                
            }
            
        }
        
        return false;
        
        
    }
    
    /**
     * Aranan bir t�rdeki i�erikleri d�nd�r�r
     * @param string $type
     */
    public function getType($type = '')
    {
        
        $files = $this->files;
   
        
        $filter = array_filter($files, function ($key) use($type){
            
            if(strstr($key, $type)){
                
               return $key;
                
            }
            
        });
        
        return $filter;
    }
    
    /**
     * Adapter s�n�f�n�n s�n�f� i�erikleyebilmesi i�in kullan�lacak
     * @return string
     */
    public function getName()
    {
        
        return "iterator";
        
    }
    
    
    /**
     * 
     */
    public function boot()
    {
        
        //
        
    }
    
    
}

?>