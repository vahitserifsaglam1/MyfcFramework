<?php
/**
 * Created by PhpStorm.
 * User: vahitşerif
 * Date: 5.4.2015
 * Time: 01:48
 */

namespace Myfc;

use Myfc\Cache\CacheInterface;
use Myfc\Helpers\DriverManager;

class Cache extends DriverManager{


   

    public function __construct( array $configs = null)
    {

        
        $this->setDriverList([

         'apc' => 'Myfc\Cache\Connector\apc',
         'file' => 'Myfc\Cache\Connector\file',
         'predis' => 'Myfc\Cache\Connector\predis',
         'memcache' => 'Myfc\Cache\Connector\memcache'

     ]);
        
        if($configs === null)
        {

             $configs = Config::get('strogeConfigs','Cache');

        }


       
        $this->boot($configs);

    }

  
  


    /**
     *
     *    sınıfa yeni bir connector ekler, autocheck true ise otomatik o driverı seçer
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

            $this->addDriver($name, $return);

            if($autocheck)
            {

                $this->connectDriver($name);

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



    /**
     * Dinamik olarak fonksiyon çağırmakta kullanılır
     * @param string $method
     * @param array $parametres
     * @return mixed
     */

    private function call($method,array $parametres = [])
    {

        if(is_callable([$this->getDriver(),$method]))
        {

            return call_user_func_array([$this->getDriver(), $method], $parametres);

        }


    }

    public function __call($method, $parametres){

        return $this->call($method, $parametres);

    }




}