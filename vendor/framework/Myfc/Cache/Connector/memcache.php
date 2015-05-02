<?php
namespace Myfc\Cache\Connector;

use Memcache as cache;

class memcache
{

    public $memcache;

    public function __construct()
    {

     
    }
    
    /**
     * 
     * @param array $configs
     */
    
    public function boot(array $configs = [] ){
        
        $cache = new cache();

        $configs = $configs['memcache'];

        $cache->connect($configs['host'], $configs['port']);

        $this->cache = $cache;

        
    }


    public function check(){
        
        if(extension_loaded('memcache')){
            
       
            return true;
            
        }
        
    }

    public function __call($name, $params)
    {

        return call_user_func_array([$this->memcache,$name],$params);

    }

}