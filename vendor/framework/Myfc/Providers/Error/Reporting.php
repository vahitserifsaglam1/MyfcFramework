<?php
namespace Myfc\Providers\Error;

/**
 *
 * @author vahit�erif
 *        
 */
class Reporting
{

    /**
     */
    public function __construct()
    {
        
        ini_set('error_reporting', REPORTING);


        $this->setErrorLogFile();
        
    }


    private function setErrorLogFile()
    {

        ini_set('error_log', APP_PATH.'Logs/error.log');

    }




}

?>