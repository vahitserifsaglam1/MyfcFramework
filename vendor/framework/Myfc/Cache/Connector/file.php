<?php


namespace Myfc\Cache\Connector;
use Myfc\File as Filesystem;
use Exception;

final class file{
    
    /**
     *
     * @var File  
     */
    
    private $filesystem;
    
    /**
     *
     * @var array 
     */
    
    public $cache;
    
    /**
     *
     * @var string 
     */
    
    private $path;
    
    /**
     * Başlatıcı fonksion
     * @param array $configs
     */
    
  
    
   public function __construct() {
     
   }
   
   public function boot(array $configs = [] ){
       
        $this->filesystem = Filesystem::boot() ;
      $this->path = $configs['file']['path'];
      
      if(!$this->filesystem->exists($this->getPath())){
          
          $this->filesystem->createDirectory($this->getPath());
          
      }
      
      if(!$this->checkIsWriteable()){
          
          throw new Exception(sprintf("%s dosyasu yazılabilir bir dosya değil",$this->getPath()));
          
      }
      $this->filesystem->in($this->getPath());
       
   }

   private function checkIsWriteable(){
       
           if($path === null){
           
           $path = $this->getPath();
           
       }else{
           
           $path = $this->filesystem->inPath($path);
           
       }
       
            (is_writeable($path)) ? true:false;  
       
   }

   public function set($name = '', $value = '', $time = 3600)
   {
       
      
       if(is_object($value) || is_array($object)){
           
           $value = serialize($value);
           
       }
       
       $cache = [
           
           'value' => $value,
           'endTime' => time()+$time,
           'time' => time()
           
       ];
       
       $content = serialize($cache);
       
       if(!$this->filesystem->exists($this->generateFileName($name))){
           
           $this->filesystem->create($this->generateFileName($name));
           
       }
       
       $this->filesystem->write($name, $content);
       
       return $this;
       
   }
   
   public function get($name){
       
       $fileName = $this->generateFileName($name);
       $content = [];
       
       
       if($this->filesystem->exists($fileName)){
           
           if(is_readable($this->filesystem->inPath($fileName))){
               
           $content = unserialize($this->filesystem->read($fileName));
               
           $endTime = $content['endTime'];
           $createdTime = $content['time'];
           $ic = $content['content'];
           
           $time = time();
           
           if($time > $endTime){
               
               $this->filesystem->delete($this->filesystem->inPath($fileName));
               return false;
               
           }else{
               
               return $ic;
               
           }
           
           }else{
               
               throw new Exception(sprintf("%s okunabilir bir dosya değil", $this->filesystem->inPath($fileName)));
               
           }
           
           
       }else{
           
           throw new Exception(sprintf("%s dosyası %s içinde bulunamadı",$fileName,$this->getPath()));
           
       }
       
   }

   private function checkIsReadable($path = null){
       
       if($path === null){
           
           $path = $this->getPath();
           
       }else{
           
           $path = $this->filesystem->inPath($path);
           
       }
       
            (is_readable($path)) ? true:false;  
   }
   
   public function flush(){
       
       $this->cache = [];
       $this->filesystem->delete($this->getPath());
       $this->filesystem->createDirectory($this->getPath());
       
   }
   
   private function generateFileName($name){
       
       return $name.".cache";
       
   }
   
   public function delete($name){
       
       
   }

      /**
    * dosyanın kaynak klosorunu getirir
    * @return string
    */
   public function getPath(){
   
       return $this->path;
       
   }
   
   public function check(){ 
        
       return true;
       
   }
   
}