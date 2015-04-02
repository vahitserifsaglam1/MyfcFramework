<?php
namespace Myfc\App;

/**
 *
 * @author vahitþerif
 *        
 */
class Clicker
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
    
    public function click($div,$callable)
    {
        
        $this->clear();
        
        $app = $this->app;
        
        $this->content .= "$('$div').click(function(e){ \n";
        
        $this->content .= $callable($app)->getContent();
        
        $this->content .= "});";
        
        return $this;
        
    }
}

?>