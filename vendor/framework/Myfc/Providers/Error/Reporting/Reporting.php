<?php
namespace Myfc\Providers\Error;

/**
 *
 * @author vahitþerif
 *        
 */
class Reporting
{

    /**
     */
    public function __construct()
    {
        
        ini_set('error_reporting', REPORTING);
        
    }
}

?>