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

class Engine{
    
    private $driverList = array(
        
        'php' => 'Myfc\Template\Connector\noTemplate',
        'smarty' => 'Myfc\Template\Connector\Smarty',
        'twig'  => 'Myfc\Template\Connector\Twig'
        
    );
    
    private $driver;
    
    private $configs;
    
    public function __construct() {
        $this->configs = Config::get('Configs','templateEngine');
        
        $this->connect($this->configs);
    }
    
    
    private function connect($driver = ''){
        
        if(isset($this->driverList[$driver])){
            $this->driver = Singleton::make($this->driverList[$driver]);
            
        }else{
            
            throw new Exception(sprint_f("%s seçtiğiniz driver yüklü değil",$driver));
            
        }
        
        
    }
    
    public function __call($name, $parametres){
        return call_user_func_array(array($this->driver, $name), $parametres);
        
    }
    
    
}