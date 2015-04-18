<?php


namespace Myfc\Template\Connector;
 use Twig_Loader_Filesystem;
 use Twig_Environment;
 use Myfc\Config;
/**
 * MyfcFramework Twig connector sınıfı 
 * 
 *  Twig template engine nin myfc frameworkde kullanılmasını sağlar
 *
 * @author vahitşerif
 */
class Twig {
    
    private $loader;
    
    private $options;
    
    private $parametres;
    public function __construct(){
        
              
              $this->options = Config::get('Configs','twig');
            
    }
    
    public function useTemplateParametres(array $parametres){
        
         $this->parametres = $parametres;
          return $this;  
        
    }
    
    public function execute($file){
        
       
        $file = $file.".twig.php";
     
        $loader = new Twig_Loader_Filesystem(VIEW_PATH);
        $this->twig = new Twig_Environment($loader, $this->options);
        $return = $this->twig->render($file, $this->parametres);
        echo $return;
    }
    
}
