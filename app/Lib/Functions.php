<?php


   set_exception_handler("default_exception_handler");


   set_error_handler('MyErrorHandler',E_ALL);

  function default_exception_handler(Exception $e)
  {
      new MyException($e->getMessage(),$e->getCode(),$e->getFile(),$e->getLine());
  }

  function MyErrorHandler($errstr, $errno, $errline, $errfile)

   {

       error::newError(array($errstr,$errno,$errline,$errfile));

   }

   function error_logOzsa($errstr, $errno = false , $errline = false , $errfile = false)
     {
         $errorConfigs = require APP_PATH."Configs/errorConfigs.php";
         $time = date('H:i');
         $date = date('d.m.Y');
          if(!$errno) $content = ">>User Error :: $errstr [ Time : $time | Date : $date ]".PHP_EOL;
            else $content = ">>System Error :: Message => $errstr | ErrorNo => $errno | ErrorFile => $errline | ErrorLine  => $errfile |  [ Time : $time | Date : $date ] ".PHP_EOL;
             $path = $errorConfigs['logFilePath'];
               if($errorConfigs['writeLog'])
                 {
                  $ac = fopen
                     ($path,"a");
                        $yaz = fwrite($ac,$content);
                           fclose($ac);
           }
     }
