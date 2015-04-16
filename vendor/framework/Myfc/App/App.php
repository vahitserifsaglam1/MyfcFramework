<?php

namespace Myfc;

use Myfc\Bootstrap;
/**
 * Class App
 * @package Myfc
 *
 *  MyfcFramework App sınıfı
 *
 *  Sınıflarda facede kısmı çağrılacak
 *
 *  Bootsrap sınıfının çağrılmasından oluşacak açığı kapatmak için kullanılacak
 */

class App{

    private $bootstrap;


    public function __construct(Bootstrap $bootstrap){

        $this->bootstrap = $bootstrap;

    }

    public function __call($name, $params){


        return call_user_func_array(array($this->bootstrap, $name),$params);

    }

}