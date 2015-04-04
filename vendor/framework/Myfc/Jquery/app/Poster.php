<?php
namespace Myfc\App;

/**
 *
 * @author vahitþerif
 *        
 */
class Poster
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
    
    public function post($div,$veriler, $callable)
    {
        
        $this->clean();
       
        $app = $this->app;
        
     
         
        if(is_array($veriler))
        {
            
            if($veriler['postUri'])
            {
                
                $posturi = $veriler['postUri'];
                
                $url = "'$posturi'";
                
            }
            
            $veriler = http_build_query($veriler);
             
        }else{
             
            $veriler = "$('$div').serialize()";
             
        }
         
  
        $this->content .= "
        $.post(";
       
       
            if(isset($veriler['postUri']))
            {
                $this->content .= "$url,";
            }else{
                $this->content .= "$('$div').attr('action'),";
            }
        
      
        
        $this->content .= "$veriler,
        function(data){";
        if(is_callable($callable)) $this->content .= $callable(clone $app)->getContent();
        
      
       
        $this->content .= " } ); \n ";
         
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