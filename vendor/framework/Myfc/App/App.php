<?php

namespace Myfc;

use Myfc\Bootstrap;

class App{

    private $bootstrap;


    public function __construct($bootstrap){

        $this->bootstrap = $bootstrap;

    }

    public function __call($name, $params){


        return call_user_func_array(array($this->bootstrap, $name),$params);

    }

}