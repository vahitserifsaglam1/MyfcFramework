<?php
namespace Myfc\App;

/**
 *
 * @author vahitþerif
 *        
 */
class Keyer
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
    
    public function keypress($div,callable $callable)
    
    {
        
        $this->clear();
        
        $this->content .= "$('$div').keypress(function(e){ \n";
        
        $this->content .= $callable($this->app)->getContent();
        
        $this->content .= "}); \n ";
         
        return $this;
        
    }
    
    public function keydown($div,callable $callable)
    {
        
        $this->clear();
        
        $this->content .= "$('$div').keydown(function(e){ \n";
        
        $this->content .= $callable($this->app)->getContent();
        
        $this->content .= "}); \n ";
         
        return $this;
        
    }
    
    public function keyup($div, callable $callable)
    {
        
        $this->clear();
        
        $this->content .= "$('$div').keyup(function(e){ \n";
        
        $this->content .= $callable($this->app)->getContent();
        
        $this->content .= "}); \n ";
         
        return $this;
        
        
    }
    
}

?>