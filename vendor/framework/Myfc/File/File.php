<?php

 namespace Myfc;

use Myfc\Adapter;
use Myfc\File\App\DirectoryIterator;
use Myfc\File\App\Finder;
use Exception;

/** 
 * @author vahit�erif
 * 
 */
class File
{
    
    public $folder;
    
    public $adapter;
    
    public $in;

    /**
     *   Ba�lat�c� fonksiyon
     *   
     *   Adapter a gerekli s�n�flar� y�kler
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
     * �stenen tipteki �zellikleri d�nd�r�r
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
     * in ile atanan klas�r ana klas�r olarak se�ilir
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
     *   Girilen path yollar�n� in e d�nd�r�r
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
     * Dosyan�n olup olmad���n� kontrol eder
     * @param unknown $path
     * @return boolean
     */
    
    public function exists($path)
    {
   
        $path = $this->inPath($path);
 
        return ( file_exists($path ) ) ? true:false;
    
    }
    
    /**
     * Dosyan�n i�eri�ini okur
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
     * Dosyan�n i�eri�ini de�i�tirir
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
     * Yeni bir klas�r olu�turur
     * @param unknown $path
     */
    public function createDirectory($path)
    {
        $this->mkdir($path);
    }
    
    /**
     * Yeni bir dosya olu�turur
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
     * Yeni bir klas�r olu�turur
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
     * Bir dosya yada klas�r� siler
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
     * Bir dosyay� bir hedeften ba�ka bir hedefe kopyalar
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
    
    public function inc($path, $parametres = null)
    {
        
        $path = $this->inPath($path);
        
        if($parametres !== null){
            
            extract($parametres);
            
        }
        
        return include $path;
        
    }
    /**
     * S�n�fta bulunmayan fonksiyonlar �nce iterator s�n�f�nda aran�r
     * E�er o s�n�fta bulunmassa 
     * finder s�n�f�nda aran�r ve oradada yoksa hata mesaj� verilir
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
            
            throw new Exception(sprintf( "%s ad�nda bir fonsiyon bulunamad�",$name));
            
        }
        
    }
}

?>