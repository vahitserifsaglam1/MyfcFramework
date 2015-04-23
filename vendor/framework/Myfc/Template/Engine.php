<?php
/**
 *  ***************************************************
 *
 *   Myfc Framework Template Engine ( Twig )
 *
 *   *****************************************************
 */
namespace Myfc\Template;

use Myfc\Config;
use Myfc\Singleton;
use Exception;
use Myfc\Template\TemplateInterface;

class Engine{
    
    private $driverList = array(
        
        'php' => 'Myfc\Template\Connector\noTemplate',
        'smarty' => 'Myfc\Template\Connector\Smarty',
        'twig'  => 'Myfc\Template\Connector\Twig',
        'MyfcTemplate' => 'Myfc\Template\Connector\MyfcTemplate'
        
    );
    
    private $driver;
    
    private $configs;
    
    public function __construct() {
        $this->configs = Config::get('Configs','templateEngine');
        
        $this->connect($this->configs);
    }
    
    /**
     * Bağlantı içini yapar
     * @param type $driver
     * @throws Exception
     */
    public function connect($driver = ''){
        
        if(isset($this->driverList[$driver])){
            $this->driver = Singleton::make($this->driverList[$driver]);
  
            
        }else{
            
            throw new Exception(sprint_f("%s seçtiğiniz driver yüklü değil",$driver));
            
        }
        
        
    }
    
    /**
     * Sınıfa eklenti eklemede kullanılır
     * @param string $name
     * @param callable $callback
     * @param boolean $autoselect
     * @return \Myfc\Template\Engine
     * 
     *  Eklentiler  Myfc\Template\TemplateInterface e ait bir instance olmak zorundadır
     */
    public function extension($name, callable $callback, $autoselect = false){
        
        if(!isset($this->driverList[$name])){
            
            $callback = $callback();
            
            
            if($callback instanceof TemplateInterface){
                
                $this->driverList[$name] = get_class($callback);
                
            }
            
        }
        
        if($autoselect){
            
            $this->connect($name);
            
        }
        
        return $this;
        
    }

  /**
   * 
   * Dinamik olarak driverlara yönlendirme fonksiyonu
   * 
   * @param string $name
   * @param array $parametres
   * @return mixed
   */
    
    public function __call($name, $parametres){
        return call_user_func_array(array($this->driver, $name), $parametres);
        
    }
    
    
}