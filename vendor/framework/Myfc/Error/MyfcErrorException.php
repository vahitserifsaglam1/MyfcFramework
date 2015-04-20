<?php

namespace Myfc\Error;
/**
 *  MyfcErrorException class
 *
 * @author vahitşerif
 */
class MyfcErrorException {
    
    public $message;
    public $code;
    public $line;
    public $file;
    
    public function __construct($errno, $errstr, $errfile, $errline) {
        $this->message = $errstr;
        $this->code = $errno;
        $this->file = $errfile;
        $this->line = $errline;
        
    }
    
    /**
     * Mesajı döndürür
     * @return type
     */
    
    public function getMessage(){
        
        return $this->message;
        
    }
    
    /**
     * Hata kodunu döndürür
     * @return numeric
     */
    public function getCode() {
        
        return $this->code;
        
    }
    
    /**
     * Satırı döndürür
     * @return type
     */
    public function getLine(){
        
        return $this->line;
        
    }
    
    /**
     * file değerini döndürür
     * @return string
     */
    public function getFile(){
        
        return $this->file;
        
    }
    
}
