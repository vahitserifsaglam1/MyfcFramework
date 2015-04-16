<?php

namespace Myfc\Template\Connector;
use Smarty as TemplateEngine;
use Myfc\File;
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
        $this->setTemplateDir(VIEW_PATH);
        $this->setCompileDir(VIEW_PATH.'smarty/compile/');
        $this->setConfigDir(APP_PATH.'Configs/');
        $this->setCacheDir(APP_PATH.'Stroge/Cache/');
        
        $this->caching = Smarty::CACHING_LIFETIME_CURRENT;
    }
    
    /**
     * Girilen parametrelerin kullanılmasını sağlar
     * @param array $parametres
     * @return \Myfc\Template\Connector\Smarty
     */
    public function useTemplateParametres(array $parametres = array()){
        
        foreach($parametres as $param => $value){
            
            $this->assign($param, $value);
            
        }
        
        return $this;
        
    }
    
    public function execute($file){
        
        $file = $file.".tpl";
        $this->display($file);
        
    }
    
    
}
