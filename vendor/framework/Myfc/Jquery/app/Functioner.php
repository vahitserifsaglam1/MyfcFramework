<?php
namespace Myfc\App;

/**
 *
 * @author vahitþerif
 *        
 */
class Functioner
{

    protected $app;
    
    protected $content;
    
    public function __construct($app)
    {
        
       $this->app = clone $app;
        $this->app->content= "";
        
    }
    
    public function clear()
    {
        
        $this->content = '';
        
    }
    
    public function getContent()
    {
        
        return $this->content;
        
    }
    
    public function func($div,$name = '', array $parameters = array(), $callable)
    {
        
    
        $this->content .= "function $name(".$this->app->parametersParser."){";

        if(is_callable($callable))
        {
            $this->content .= $callable($this->app);
        }else{ 
           
            $this->content .= $callable;
            
        }
        
        $this->content .= "}";
            
         
        return $this;
        
    }
}

?>