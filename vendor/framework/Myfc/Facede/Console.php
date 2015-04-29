<?php

namespace Myfc\Facade;

use Myfc\Facade;

class Console extends Facade{
    
    protected static function getFacadeClass() {
        return  "Console";
    }
    
}