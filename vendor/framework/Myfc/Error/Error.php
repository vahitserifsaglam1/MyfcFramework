<?php

 namespace Myfc;
 use Exception;
 
 class Error {

       private $exception;
       
       private $type;
       
       const EXCEPTION_HANDLER = "Exception";
       
       const ERROR_HANDLER = "Error";
       /**
        * Exception sınıfını başlatırs
        * @param Exception $e
        */
       
       
       public function __construct(Exception $e = null, $type = self::ERROR_HANDLER){
           
         $this->exception = $e;
         
         $this->type = $type;
         
         $this->echoMessage($this->generateMessage());
           
       }
       
       /**
        * 
        * @param string $message
        */
       private function echoMessage($message = ''){
           
           echo $message;
           
       }

       /**
        * Mesaj oluşturur
        * @return string
        */

       private function generateMessage(){
           
           $message = $this->exception->getMessage();
           $file = $this->exception->getFile();
           $code = $this->exception->getCode();
           $line = $this->exception->getLine();
           
           $message = "MyfcFramework Error Sınıfı yeni bir hata({$this->type}) yakaladı:  "
                   . " <p>Mesaj :<b>$message</b></p>"
                   . " <p>Dosya :<b>$file</b></p> "
                   . " <p>Satır :<b>$line</b></p> "
                   . " <p>HataC :<b>$code</b></p>";
           
           return $message;
       }
       
       public function setException($exception){
           
           $this->exception = $exception;
           
           return $this;
           
       }


 }