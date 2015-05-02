<?php


namespace Myfc\Session\Connector;


class php{
    
    public function boot(){
        
        
    }

        public function check()
    {
        
        if(isset($_SESSION)) return true;else return false;
        
    }
    
    public  function set($name,$value)
    {
    
        $_SESSION[$name] = $value;
    }
    public  function flush()
    {
        foreach($_SESSION as $key => $value)
        {
            unset($_SESSION[$key]);
        }
    }
    public  function get($name)
    {
        if(isset($_SESSION[$name])) return $_SESSION[$name];else return false;
    }
    public  function delete($name)
    {
        if(isset($_SESSION[$name])) unset($_SESSION[$name]);else error::newError(" $name diye bir session bulunamadı ");
    }
    
    
}