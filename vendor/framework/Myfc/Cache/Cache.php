<?php
/**
 * Created by PhpStorm.
 * User: vahitşerif
 * Date: 5.4.2015
 * Time: 01:48
 */

namespace Myfc;

use Myfc\Cache\CacheInterface;

class Cache {


     private $connector;

     private $configs;

     public $driverList = [

         'apc' => 'Myfc\Cache\Connector\apc',
         'file' => 'Myfc\Cache\Connector\file',
         'predis' => 'Myfc\Cache\Connector\predis',
         'memcache' => 'Myfc\Cache\Connector\memcache'

     ];

    public function __construct( array $configs = null)
    {

        if($configs === null)
        {

             $configs = Config::get('strogeConfigs','Cache');

        }


        $this->connectToConnector($this->configs);

    }

    /**
     * Bağlayıcıya bağlanır
     * @param array $configs
     * @return mixed
     */

    private function connectToConnector(array $configs = [])
    {

        $driver = $configs['driver'];

        $default = $configs['default'];

        if(!isset($this->driverList[$driver]))
        {

            $driver = $this->driverList[$default];

        }

        if($connector = $this->driverList[$driver])
        {

            $connector = new $connector($configs);

            if($connector->check())
            {

                return $connector;

            }

        }


    }


    /**
     *
     *  sınıfa yeni bir connector ekler, autocheck true ise otomatik o driverı seçer
     *
     *    $call dan dönen değer bir SessionInterface e ait olmalıdır,
     *
     *    $name eklentinin ismidir
     *
     * @param string $name
     * @param callable $call
     * @param boolean $autocheck
     * @return boolean
     */
    public function extension($name, callable $call, $autocheck = false)
    {

        $return = $call();
        
        // gelen sınıfın bir cacheınterfa e ait olup olmadığna bakıyoruz

        if($return instanceof CacheInterface)
        {

            $this->driverList[$name] = get_class($return);

            if($autocheck)
            {

                $this->driver($name);

            }

            return true;

        }else{

            return false;

        }

    }

    /**
     * driver seçimi yapar
     * @param string $name
     */

    public function driver($name)
    {

        if(isset($this->driverList[$name]))
        {

            $this->configs = $this->configs['driver'] = $name;

        }

    }

    /**
     * Dinamik olarak fonksiyon çağırmakta kullanılır
     * @param string $method
     * @param array $parametres
     * @return mixed
     */

    private function call($method,array $parametres)
    {

        if(is_callable([$this->connector,$method]))
        {

            return call_user_func_array([$this->connector, $method], $parametres);

        }


    }

    public function __call($method, $parametres){

        return $this->call($method, $parametres);

    }




}