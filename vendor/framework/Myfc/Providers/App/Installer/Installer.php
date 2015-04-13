<?php

namespace Myfc\Providers\App;

class Installer{

    public function __construct($bootstrap){

        $bootstrap->singleton('Myfc\App', $bootstrap);

    }

}