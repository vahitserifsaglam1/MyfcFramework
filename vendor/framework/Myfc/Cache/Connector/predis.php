<?php

namespace Myfc\Cache\Connector;

 use Myfc\Config;
 use Myfc\Predis\Installer;
 use PredisClient;

class predis
{

    public $predis;

    public function __construct( )
    {
        $configs = Config::get('strogeConfigs','predis');

        Installer::boot();
        $this->predis = Installer::create($configs['predis']);

    }

    public function __call( $name,$params )
    {

        return call_user_func_array([$this->predis,$name],$params);

    }

    public function check()
    {


         if($this->predis instanceof PredisClient)
         {

             return true;

         }


    }



}