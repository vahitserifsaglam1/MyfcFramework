<?php
/**
 * Description of DriverManager
 *
 * @author vahitşerif
 */
namespace Myfc\Helpers;
use Myfc\Helpers\DriverManagerInterface;
use Exception;
abstract  class DriverManager {
    
    
    protected $driverList;
    
    protected $configs;
    
    protected $driverName;
    
    protected $driver;


    protected function boot($configs = []){
        
        $this->configs = $configs;
        $this->driverName = $configs['driver'];
        
      
        $this->connectDriver($this->getDriverName());
        
        return $this;
        
    }
    
    protected function addDriver($name,DriverManagerInterface $instance){
        
        $this->driverList[$name] = $instance;
        return $this;
        
    }

        /**
     * Seçilecek driverin adını döndürür
     * @return type
     */
    public function getDriverName(){
        
        return $this->driverName;
        
    }
    
    /**
     * Kurulan Driver ı döndürür
     * @return type
     */
    public function getDriver()
    {
        
        return $this->driver;
        
    }

    
    protected function setDriverList( array $drivers = [] ) {
        
        $this->driverList = $drivers;
        return $this;
        
    }
    
    /**
     * Ayarları döndürür
     * @return type
     */
    public function getConfigs(){
        
        return $this->configs;
        
    }
    
    /**
     * Driver a bağlanılır
     * @param type $driverName
     * @return \Myfc\Helpers\driver
     * @throws Exception
     */
    protected function connectDriver($driverName = ''){
        
        if($this->isDriver($driverName)){
            
            $connect = $this->generateDriverInstance($driverName);
           
            if($connect->check()){
                
                 $this->driver =  $connect;
                 $this->getDriver()->boot($this->getConfigs());
                
            }
           
            
        }else{
            
            throw new Exception(sprintf("%s driver ı driverlarınız arasında bulunamadı"));
            
        }
        
    }
    
    /**
     * Sürücünün yüklü olup olmadığına bakar
     * @param type $driverName
     * @return type
     */
    
    protected function isDriver($driverName){
        
        return (isset($this->driverList[$driverName])) ? true:false;
        
        
    }
    
    
    protected function generateDriverInstance($driverName){
        
        return new $this->driverList[$driverName]();
        
    }
    
    
   
    
    
    
    
}
