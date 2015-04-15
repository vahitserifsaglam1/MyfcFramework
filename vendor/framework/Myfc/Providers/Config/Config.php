<?php

/*
 * 
 * Myfc framework timezone ayarlamak için kullanılacak providers
 * 
 */

namespace Myfc\Providers;
use Myfc\Bootstrap;
/**
 *
 * @author vahitşerif
 */
class Config {

     public function __construct(Bootstrap $bootstrap) {
         date_default_timezone_set($bootstrap->configs['timezone']) ;
     }
    
}
