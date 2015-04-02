<?php
namespace Myfc\App;

/**
 *
 * @author vahitþerif
 *        
 */
class Animater
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
    
    public function animate($div,$array,$ms,$callable)
    {
        
        
         $app = $this->app;
         
         $encoder = $app->encoder($array);
         
         $content = "";
         
         $content .= "$('$div').animate($encoder,$ms,function(){";
          
         $content .= $callable($app)->getContent();
          
         $content .= "}); \n";
          
         $this->content .= $content;
     
         return $this;
        
    }
    
    public function fadeIn($div, $time,$callback)
    {
        
        $app = $this->app;
        

        
        $this->clear();
        
        if(!is_numeric($time))
        {
            
            $time = "'$time'";
            
        }
        
        $content = "";
        
        $content .= "$('$div').fadeIn($time,function(){";
        
        $content .= $callback($app)->getContent();
        
        $content .= "}); \n";
        
        $this->content .= $content;
        
        return $this;
    }
    
    public function fadeOut($div,$time,$callback)
    {
        
        $app = $this->app;
              
        $this->clear();
        
        if(!is_numeric($time))
        {
        
            $time = "'$time'";
        
        }
        
        $content = "";
        
        $content .= "$('$div').fadeOut($time,function(){";
        
        $content .= $callback($app)->getContent();
        
        $content .= "}); \n";
        
        $this->content .= $content;
        
        return $this;
        
    }
    
    public function fadeTo($div,$time,$opacity,$callback)
    {
    
        $app = $this->app;
    
    
        $this->clear();
    
        if(!is_numeric($time))
        {
    
            $time = "'$time'";
    
        }
        
        $content = "";
    
        $content .= "$('$div').fadeTo($time,$opacity,function(){";
    
        $content .= $callback($app)->getContent();
    
        $content .= "}); \n";
        
        $this->content .= $content;
        
        return $this;
    
    }
    
    public function slideDown($div,$time,$callback)
    {
    
        $app = $this->app;
    
    
        $this->clear();
    
        if(!is_numeric($time))
        {
    
            $time = "'$time'";
    
        }
        
        $content = "";
    
        $content .= "$('$div').slideDown($time,function(){";
    
        $content .= $callback($app)->getContent();
    
        $content .= "}); \n";
        
        $this->content .= $content;
        
        return $this;
    
    }
    
    public function slideUp($div,$time,$callback)
    {
    
        $app = $this->app;
    
    
        $this->clear();
    
        if(!is_numeric($time))
        {
    
            $time = "'$time'";
    
        }
        
        $content = "";
    
        $content .= "$('$div').slideUp($time,function(e){";
    
        $content .= $callback($app)->getContent();
    
        $content .= "}); \n";
        
        $this->content .= $content;
        
        return $this;
    
    }
    
    public function slideToggle($div,$time,$callback)
    {
    
        $app = $this->app;
    
        $this->clear();
    
        if(!is_numeric($time))
        {
    
            $time = "'$time'";
    
        }
        
        $content = "";
    
        $content .= "$('$div').slideToggle($time,function(e){";
    
        $content .= $callback($app)->getContent();
    
        $content .= "}); \n";
        
        $this->content .= $content;
        
        return $this;
    
    }
}

?>