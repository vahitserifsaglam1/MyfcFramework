<?php

namespace Myfc\Providers\Route;

use Myfc\Bootstrap;

class Runner{

    public function __construct(Bootstrap $bootstrap)
    {

        include APP_PATH.'Route.php';

        $router = new Router($bootstrap, Route::getCollection());



    }

}