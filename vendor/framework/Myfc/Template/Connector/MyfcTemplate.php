<?php

namespace Myfc\Template\Connector;
use Myfc\Template\MyfcTemplate as Engine;
use Myfc\Template\MyfcTemplate\MyfcTemplateExtensionManager;
/**
 * Description of MyfcTemplate
 *
 * @author vahitşerif
 */
class MyfcTemplate {

    private $engine;
    
    public function __construct() {
        
        $manager = new MyfcTemplateExtensionManager;
        $this->engine = new Engine( $manager);
        
        
    }
    
    public function useTemplateParametres(array $parametres =[]){
        
        $this->engine->assing($parametres);
        return $this;
        
    }
    
    public function display($file ,$parametres = []){
        
        $this->engine->display($file, $parametres);
        
    }
     
}
