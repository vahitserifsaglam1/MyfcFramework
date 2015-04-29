<?php

namespace Myfc\Template\Connector;
use Smarty as TemplateEngine;
use Myfc\File;
use Myfc\Config;
/**
 * Myfc framework smary template desing connector sınıfı 
 * 
 *  Smarty template engine in myfc framework de kullanılmasını sağlar
 *
 * @author vahitşerif
 */
class Smarty extends TemplateEngine{
    
    private $file;
    
    public function __construct(){
        
        $this->file = new File();
        parent::__construct();
        $configs = Config::get('Configs','smarty');
        $this->setTemplateDir($configs['templateDir']);
        $this->setCompileDir($configs['compileDir']);
        $this->setConfigDir($configs['configDir']);
        $this->setCacheDir($configs['cacheDir']);    
        $this->caching = $configs['cacheTime'];
    }
    
    /**
     * Girilen parametrelerin kullanılmasını sağlar
     * @param array $parametres
     * @return \Myfc\Template\Connector\Smarty
     */
    public function useTemplateParametres(array $parametres = []){
        
        foreach($parametres as $param => $value){
            
            $this->assign($param, $value);
            
        }
        
        return $this;
        
    }
    
    /**
     * İçerik gösterir
     * @param string $file
     */
    public function execute($file){
        
        $file = $file.".tpl";
        $this->display($file);
        
    }
    
    
}
