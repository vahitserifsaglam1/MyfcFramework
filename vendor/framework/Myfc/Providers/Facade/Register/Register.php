<?php
/**
 * Created by PhpStorm.
 * User: vahitşerif
 * Date: 5.4.2015
 * Time: 12:05
 */

namespace Myfc\Providers\Facade;

use Myfc\Facade;
use Myfc\Bootstrap;

class Register {

     public function __construct(Bootstrap $bootstrap)
     {

         $alias = $bootstrap->configs['alias'];
         Facade::$instance = $alias;

     }

}