<?php
namespace Myfc\Providers\Event;
use Myfc\Bootstrap;
class Installer{

    public function __construct(Bootstrap $bootstrap)
    {


        $eventPath = APP_PATH.'Events.php';

        if(file_exists($eventPath))
        {

            include $eventPath;

        }


    }

}