<?php
namespace Myfc\File\App;

 use Exception;
/**
 *
 * @author vahitþerif
 * 
 *  Seçilen dosyanýn alt, üst , tip , boyut vs özellikleri bulunur
 *        
 */
class Finder
{

    public $file;
    
    private $type;
    
    public $index = array();
    
    /**
     * Sýnýfý baþlatýr ve eðer file null girilmemiþse dosyanýn özelliklerini indexler
     * @param string $file
     */
    public function __construct( $file = null)
    {
        
        if($file !== null)
        {
           
            
            $this->indexFile($file);
            
        }
        
    }
    
    /**
     * Dosyanýn özelliklerini okur
     * @param unknown $file
     */
    
    public function indexFile( $file = '')
    {
        
        if(file_exists($file))
        {
            
             $this->file = $file;
            
             $this->index['size'] = filesize($file);
             
             $this->index['name'] = $this->nameFinder($file);
             
             $this->index['type'] = $this->typeFinder($file);
             
             $this->index['time'] = filectime($file);
             
             $this->index['realpath'] = $this->realPathFinder($file);
             
             $this->index['fullName'] = $this->index['name'].".".$this->index['type'];
             
             return $this;
            
        }
        
    }
    
    /**
     * Dosyanýn adýný bulur
     * @param string $file
     * @return mixed
     */
    
    protected function nameFinder($file = null)
    {
        
         if(strstr($file, "/"))
         {
             
             $file = substr($file, 0, strlen($file)-1);
             
         }
         
         
         $parcala = explode("/", $file);
         
         $end = end($parcala);
         
         if(strstr($end,"."))
         {
             
             $typeAyir = explode(".",$end);
              
             $this->type = end($typeAyir);
             
             $cikacak = $typeAyir[array_search($this->type, $typeAyir)];
             
             unset($cikacak);
             
             return $typeAyir[0];
             
         }else{
             
             return $end;
             
         }
         
         
         
        
    }
    
    /**
     * Dosyanýn tipini döndürür
     * @param unknown $file
     * @return mixed
     */
    
    protected function typeFinder($file)
    {
        
        if($file === $this->file)
        {
            
            return $this->type;
            
        }else{
            
            if(strstr($file, "/"))
            {
                 
                $file = substr($file, 0, strlen($file)-1);
                 
            }
             
            $parcala = explode("/", $file);
             
            $end = end($parcala);
             
            $typeAyir = explode(".",$end);
             
            $type = end($typeAyir);
             
            return $type;
            
        }
        
    }
    
    /**
     * Üst klasörlere döndüren kod
     * @param number $folder
     * @return string|boolean
     */
    
    public function parentFolder($folder = 1)
    {
        
        if($folder > 1)
        {
            
            $s = "";
            
            for($i = 0;$i<=$folder;$i++)
            {
                
              $s .= "../";   
                
            }
            
            $folder = $s.$folder;
            
            return $folder;
            
        }else{
            
            
            return false;
            
        }
        
    }
      
    
    /**
     * 
     * @param string $file
     * @return string
     */
    
    
    
    public function get()
    {
        
        return $this->index;
        
    }
    
    protected function realPathFinder($file = '')
    {
        
     return realpath($file);
        
    }
    
    /**
     * Adapter dan çaðýrýlýrken kullanýlacak ismi
     * @return string
     */
    
    public function getName()
    {
        
        return "finder";
        
    }
    
    /**
     * Adapter standart fonksiyonlarý
     */
    
    public function boot()
    {
        
        //
        
    }
    
    public function __get($name)
    {
        
        if(isset($this->index[$name]))
        {
            
            return $this->index[$name];
            
        }else{
            
            throw new Exception( sprintf( "%s adýnda bir parametre bulunamadý", $name));
            
        }
        
    }
}

?>