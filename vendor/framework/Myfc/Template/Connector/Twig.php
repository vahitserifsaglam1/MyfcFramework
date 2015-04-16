<?php


namespace Myfc\Template\Connector;
 use Twig_Autoloader;
 use Twig_Loader_Filesystem;
 use Twig_Environment;
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
        
        Twig_Autoloader::register();
              $this->options = [
             'debug' => false,
             'charset' => 'utf-8',
             'cache' => './cache', // Store cached files under cache directory
             'strict_variables' => true,]; 
            
    }
    
    public function useTemplateParametres(array $parametres){
        
         $this->parametres = $parametres;
          return $this;  
        
    }
    
    public function execute($file){
        
        $file = $file.".twig.php";
        $filepath = VIEW_PATH.$file;
        
        $loader = new Twig_Loader_Filesystem($filepath);
        $this->twig = new Twig_Environment($loader, $this->options);
        $return = $this->twig->render($file, $this->parametres);
        echo $return;
    }
    
}
