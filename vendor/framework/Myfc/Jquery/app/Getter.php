<?php
namespace Myfc\App;

/**
 *
 * @author vahitþerif
 *        
 */
class Getter
{

    protected $content;
    
    protected $app;
    
    function __construct($app)
    {
        
        $this->app = clone $app;
        $this->app->content= "";
        
    }
    
    public function clean()
    {
        $this->content = "";
    }
    
    public function get($div,$veriler, $callable)
    {
        
        $this->clean();
       
        $app = $this->app;
        
     
         
        if(is_array($veriler))
        {
             
            $veriler = http_build_query($veriler);
             
        }else{
             
            $veriler = "$(this).serialize()";
             
        }
         
  
        $this->content .= "   
        $.get(
        $('$div').attr('action'),
        $veriler,
        function(data){";
       if(is_callable($callable)) $this->content .= $callable(clone $app)->getContent();
        
      
       
        $this->content .= "      } ); \n ";
         
        return  $this->content;
        
        
    }
    
    
    public function getContent()
    {
        
        return $this->content;
        
    }
    public function __call($name,$params)
    {
    
        $call = call_user_func_array(array($this->app,$name), $params);
    
        if(is_string($call))
        {
    
            $this->content .= $call;
    
        }
    
        if(is_object($call))
        {
    
            $this->content .= $call->getContent();
    
        }
    
        return $this;
    
    }
}

?>