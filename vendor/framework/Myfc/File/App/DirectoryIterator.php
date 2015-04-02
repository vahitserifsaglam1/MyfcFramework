<?php
namespace Myfc\File\App;

/**
 *
 * @author vahitþerif
 * 
 *  Sýnýflarda klasörlerin içeriklerini, klasörlerini listelemek için kullanýlacak
 *        
 */
class DirectoryIterator
{
    /*
     *  @var $folder
     *  
     *  Seçilen klasör tutulur
     */
    public $folder;
    
    
    
    public $folders = array();
    
    public $files=array();
    
    public $mixed = array();
    
    /**
     * 
     * Sýnýfý baþlatýr eðer bir dosya giriþi yapýlmýþsa onu listeler
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
     * Klasördeki dosyalarý okur
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
     * Klasördeki klasörleri okur
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
     *   Dosyalarý Döndürür
     * 
     * @return multitype:
     */
    public function getMixed()
    {
        
        return $this->mixed;
        
    }
    
    /**
     *  
     *   Klasörleri Döndürür
     * 
     * @return multitype:
     */
    public function getFolders()
    {
        
        return $this->folders;
        
    }
    
    /**
     *  
     *   Tüm içeriði Döndürür
     *  
     * @return multitype:
     */
    public function getFiles()
    {
        
        return $this->files;
        
    }
    
    /**
     * Listelemede noktalarý temizlek için kullanýlacak
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
     * Girilen yoldaki içeriðin dosya olup olmadýðýný kontrol eder
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
     * Girilen yoldaki içeriðin klasör olup olmadýðýný kontrol eder
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
     * Aranan bir türdeki içerikleri döndürür
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
     * Adapter sýnýfýnýn sýnýfý içerikleyebilmesi için kullanýlacak
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