<?php
namespace Myfc\File\App;

 use Exception;
/**
 *
 * @author vahit�erif
 * 
 *  Seçilen dosyanın alt, üst , tip , boyut vs �zellikleri bulunur
 *        
 */
class Finder
{

    public $file;
    
    private $type;
    
    public $index = array();
    
    /**
     * S�n�f� ba�lat�r ve e�er file null girilmemi�se dosyan�n �zelliklerini indexler
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
     * Dosyan�n �zelliklerini okur
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
     * Dosyan�n ad�n� bulur
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
     * Dosyan�n tipini d�nd�r�r
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
     * �st klas�rlere d�nd�ren kod
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
     * Adapter dan �a��r�l�rken kullan�lacak ismi
     * @return string
     */
    
    public function getName()
    {
        
        return "finder";
        
    }
    
    /**
     * Adapter standart fonksiyonlar�
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
            
            throw new Exception( sprintf( "%s ad�nda bir parametre bulunamad�", $name));
            
        }
        
    }
}

?>