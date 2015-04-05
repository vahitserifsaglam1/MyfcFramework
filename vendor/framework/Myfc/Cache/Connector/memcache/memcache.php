<?php
namespace Myfc\Cache\Connector;

use Memcache as cache;

class memcache
{

    public $memcache;

    public function __construct(array $configs)
    {

        $cache = new cache();

        $configs = $configs['memcache'];

        $cache->connect($configs['host'], $configs['port']);

        $this->cache = $cache;

    }

    public function __call($name, $params)
    {

        return call_user_func_array(array($this->memcache,$name),$params);

    }

}