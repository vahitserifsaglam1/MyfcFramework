<?php

namespace Myfc\Facade;

use Myfc\Facade;

class Console extends Facade{
    
    public static function getFacadeClass() {
        return  "Console";
    }
    
}