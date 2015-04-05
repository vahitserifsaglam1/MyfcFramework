<?php
/**
 * Created by PhpStorm.
 * User: vahitşerif
 * Date: 5.4.2015
 * Time: 01:58
 */

namespace Myfc\Cache\Connector;


class apc {

    public $caches;

    /**
     * @param $name
     * @return mixed
     * Apc den veriyi döndürür
     */
    public function get($name)
    {
        return apc_fetch($name);
    }

    /**
     * @param $name
     * @param $value
     * @param int $time
     * @return array|bool
     * Apc e veri ataması yapar
     */
    public function set($name,$value, $time = 0)
    {
        $this->caches[$name] = $value;
        return apc_store($name,$value,$time);
    }

    /**
     * @param $name
     * @return bool|\string[]
     *
     * apc den veri silme işlemi yapar
     */
    public function delete($name)
    {
        if(isset($this->caches[$name])) unset($this->caches[$name]);
        return apc_delete($name);
    }

    /**
     * Tüm cacheleri temizler
     */
    public function flush()
    {
        if(isset($this->caches))
        {
            foreach($this->caches as $key)
            {
                apc_delete($key);
            }
        }
    }

    /**
     * @return bool
     *
     * Apc olup olmadığını kontrol eder
     */

    public function check()
    {

        if(function_exists('apc_fetch'))
        {

            return true;

        }

    }

}