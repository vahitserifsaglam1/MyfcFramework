<?php
 /**
 ***************************************************
 * Sınıflarda kullanılan hata tanımlama sınıf
 * Sonraki Sürümlerde bu kaldırılacak
 *************************************************
 */ 
class error
{

    public static function newError($error)
    {

            $errstr = $error[0];
            $errno = $error[1];
            $errline = $error[2];
            $errfile = $error[3];
            
            if($errno)
            {
               new MyException($errno,$errstr,$errline,$errfile);
            }else{

             new MyException($errstr,1);
                
            }
            
           
            error_logOzsa($errno,$errstr,$errline,$errfile);
        
    }

 
}


