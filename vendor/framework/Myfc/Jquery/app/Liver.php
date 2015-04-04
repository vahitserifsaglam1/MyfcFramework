<?php
namespace Myfc\App;

/**
 *
 * @author vahitþerif
 *        
 */
class Liver
{

    protected $app;
    
    protected $content;
    
    public function __construct($app)
    {
        
        $this->app =  $app;
        
    }
    
    public function clear()
    {
        
        $this->content = '';
        
    }
    
    public function getContent()
    {
        
        return $this->content;
        
    }
    
    public function live($div,$event,$callable)
    {
        
        
        if(is_string($event) && $callable !== null)
        {
             
            $div = $this->app->getSelectedDiv();
            $this->content .= "$('$div').live('$event',function(){ \n";
            if(is_callable($callable)) $this->content .= $callable($this->app)->getContent();
            $this->content .= "});";
             
        }else{
             
            if(is_array($event) && $callable === null)
            {
                 
                $this->content .= "$('$div').live($this->app->functionEncoder($event)); \n";
                 
            }
             
        }
         
        return $this;
        
    }
}

?>