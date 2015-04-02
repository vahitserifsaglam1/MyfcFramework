<?php

 namespace Myfc;

use Myfc\Adapter;
use Myfc\File\App\DirectoryIterator;
use Myfc\File\App\Finder;
use Exception;

/** 
 * @author vahitþerif
 * 
 */
class File
{
    
    public $folder;
    
    public $adapter;
    
    public $in;

    /**
     *   Baþlatýcý fonksiyon
     *   
     *   Adapter a gerekli sýnýflarý yükler
     */
    public function __construct()
    {
        
        $this->adapter = new Adapter('filesystem');
        
        $this->adapter->addAdapter( new DirectoryIterator());
        $this->adapter->addAdapter( new Finder());
        
    }
    
    /**
     * 
     * @return Filesystem
     */
    public static function boot()
    {
        
        return new static();
        
    }
    
    public function getIndex($path)
    {
        
        $path = $this->inPath($path);
        
        $index = $this->adapter->finder->indexFile($path)->get();
        
        return $index;
        
    }
    
    /**
     * Ýstenen tipteki özellikleri döndürür
     * @param unknown $type
     * @param string $path
     */
    public function getType($type,$path = null)
    {
        
        if($path == null)
        {
            
            $path = $this->inPath($path);
            
        }

        
        $array = $this->adapter->iterator->read($path);
        
        return $this->adapter->iterator->getType($type);
        
    }
    
    /**
     * in ile atanan klasör ana klasör olarak seçilir
     * @param unknown $path
     * @return Filesystem
     */
    
    public function in($path)
    {
        
        $son = substr($path, strlen($path)-1,strlen($path));
        
        if($son == "/"){
            
            $this->in = $path;
            
        }else{
            
            $this->in = $path."/";
            
        }
        
        return $this;
        
    }
    
    /**
     *  
     *   Girilen path yollarýný in e döndürür
     * 
     * @param unknown $path
     * @return string|unknown
     */
    
    public function inPath($path){
        
        if($this->in !== null)
        {
            
            return $this->in.$path;
            
        }else{
            
            return $path;
            
        }
        
    }
    
    /**
     * Dosyanýn olup olmadýðýný kontrol eder
     * @param unknown $path
     * @return boolean
     */
    
    public function exists($path)
    {
   
        $path = $this->inPath($path);
 
        return ( file_exists($path ) ) ? true:false;
    
    }
    
    /**
     * Dosyanýn içeriðini okur
     * @param unknown $filename
     * @param string $remote
     * @return string|unknown
     */
    
    public function read($filename,$remote=false){
        $filename = $this->inPath($filename);
        if (!$remote){
            if (file_exists($filename)){
                $handle = fopen($filename, "r");
                $content = fread($handle, filesize($filename));
                fclose($handle);
                return $content;
            }
            else {return "The specified filename does not exist";}
        }
        else{
    
            $content = file_get_contents($filename);
            return $content;
        }
    
    }
    
    /**
     * Dosyanýn içeriðini deðiþtirir
     * @param unknown $data
     * @param unknown $filename
     * @param string $append
     * @return boolean
     */
    
    public function write($data,$filename,$append=false){
        
        $filename = $this->inPath($filename);
        
        if (!$append){$mode="w";} else{$mode="a";}
        if($handle = fopen($filename,$mode)){
            fwrite($handle, $data);
            fclose($handle);
            return true;
        }
        return false;
    }
    
    /**
     * Yeni bir klasör oluþturur
     * @param unknown $path
     */
    public function createDirectory($path)
    {
        $this->mkdir($path);
    }
    
    /**
     * Yeni bir dosya oluþturur
     * @param unknown $path
     * @return unknown
     */
    
    public function create($path)
    {
    
        $path = $this->inPath($path);
        
        if(!file_exists($path))
        {
    
            touch($path);
    
        }
    
        return $path;
    
    }
    
    /**
     * Yeni bir klasör oluþturur
     * @param unknown $path
     * @return boolean
     */
    public  function mkdir($path) {
        
        $path = $this->inPath($path);
        
        $path = str_replace("\\", "/", $path);
        
        if(!file_exists($path))
        {
            
            mkdir($path,0777,true);
            
        }
    
        return true;
    }
    
    /**
     * Bir dosya yada klasörü siler
     * @param unknown $src
     * @return boolean
     */
    public function delete($src){
        $src = $this->inPath($src);
        if(is_dir($src) && $src != ""){
            $result = $this->Listing($src);
    
            // Bring maps to back
            // This is need otherwise some maps
            // can't be deleted
            $sort_result = array();
            foreach($result as $item){
                if($item['type'] == "file"){
                    array_unshift($sort_result, $item);
                }else{
                    $sort_result[] = $item;
                }
            }
    
            // Start deleting
            while(file_exists($src)){
                if(is_array($sort_result)){
                    foreach($sort_result as $item){
                        if($item['type'] == "file"){
                            @unlink($item['fullpath']);
                        }else{
                            @rmdir($item['fullpath']);
                        }
                    }
                }
                @rmdir($src);
            }
            return !file_exists($src);
        }else{
            @unlink($src);
            return !file_exists($src);
        }
    }
    
    /**
     * Bir dosyayý bir hedeften baþka bir hedefe kopyalar
     * @param unknown $src
     * @param unknown $dest
     * @return boolean
     */
    function copy($src, $dest){
    
         $src = $this->inPath($src);
         
         $dest = $this->inPath($dest);
        // If source is not a directory stop processing
        if(!is_dir($src)) return false;
    
        // If the destination directory does not exist create it
        if(!is_dir($dest)) {
            if(!mkdir($dest)) {
                // If the destination directory could not be created stop processing
                return false;
            }
        }
    
        // Open the source directory to read in files
        $i = new \DirectoryIterator($src);
        foreach($i as $f) {
            if($f->isFile()) {
                copy($f->getRealPath(), "$dest/" . $f->getFilename());
            } else if(!$f->isDot() && $f->isDir()) {
                $this->copy($f->getRealPath(), "$dest/$f");
            }
        }
    }
    
    public function move($src, $dest){
    
         $scr = $this->inPath($src);
         
         $dest = $this->inPath($dest);
        // If source is not a directory stop processing
        if(!is_dir($src)) {
            rename($src, $dest);
            return true;
        }
    
        // If the destination directory does not exist create it
        if(!is_dir($dest)) {
            if(!mkdir($dest)) {
                // If the destination directory could not be created stop processing
                return false;
            }
        }
    
        // Open the source directory to read in files
        $i = new \DirectoryIterator($src);
        foreach($i as $f) {
            if($f->isFile()) {
                rename($f->getRealPath(), "$dest/" . $f->getFilename());
            } else if(!$f->isDot() && $f->isDir()) {
                $this->move($f->getRealPath(), "$dest/$f");
                @unlink($f->getRealPath());
            }
        }
        @unlink($src);
    }
    
    public function inc($path)
    {
        
        $path = $this->inPath($path);
        
        return include $path;
        
    }
    /**
     * Sýnýfta bulunmayan fonksiyonlar önce iterator sýnýfýnda aranýr
     * Eðer o sýnýfta bulunmassa 
     * finder sýnýfýnda aranýr ve oradada yoksa hata mesajý verilir
     * @param unknown $name
     * @param unknown $params
     * @throws Exception
     * @return mixed
     */
    public function __call($name,$params)
    {
        if(method_exists($this->adapter->iterator, $name))
        {
            
            return call_user_func_array(array($this->adapter->iterator,$name), $params);
            
        }elseif(method_exists($this->adapter->finder, $name))
        {
            
            return call_user_func_array(array($this->adapter-finder,$name), $params);
            
        }else{
            
            throw new Exception(sprintf( "%s adýnda bir fonsiyon bulunamadý",$name));
            
        }
        
    }
}

?>