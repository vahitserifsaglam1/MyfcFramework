<?php

/*
 *  MyfcFramework Csrf protection sınıfı
 *  Lisans yoktur,
 *  Alınabilir, kopyalabilir, kullanılabilir
 */

namespace Myfc\Security;

use Myfc\Crypt;
use Myfc\Facade\IP;
use Myfc\Facade\Session;
use Myfc\Facade\Assets;

/**
 *
 * @author vahitşerif
 */
class Csrf extends Crypt{
    
    const SESSION_KEY = '_CRSF_PROTECTION';
    
    const SESSION_RANDOMIZER_KEY = '_CRSF_RANDOMIZER_KEY';
    
    const AYRAC = ',';

    /**
     *
     * @var string 
     */
    
     private $ip = '';
    
     
     /**
      * 
      *  Başlatıcı Fonksiyondur, kullanıcın ip adresini ve random bir sayıyı atar
      * 
      */

     public function __construct(){
         
         $this->ip = IP::getIP();
         
     }
     
     /**
      * Crsf korumasını başlatır
      * @return \Myfc\Security\Csrf
      */
     public function active(){
         $rand = md5(rand(1567,999999999));
         $random = $this->encode(base64_encode($rand));
         $message = $this->encode($this->ip).self::AYRAC.$random;
         $crypted = $this->encode(base64_encode($message));
         Session::set(self::SESSION_KEY,$message);
         Session::set(self::SESSION_RANDOMIZER_KEY, $random);
         
         return $message;
     }
     
     /**
      * Csrf korumasını devreden çıkarır
      * @return boolean
      */
     public function deactive(){
         
         $random = base64_decode($this->decode(Session::get(self::SESSION_RANDOMIZER_KEY)));
         $message = base64_decode($this->decode(Session::get(self::SESSION_KEY)));
         
         $post = Assets::clear($_POST);
         
         list($code , $rand) = explode(self::AYRAC, $message);
         
         if($rand ===  $random && isset($code) && is_string($code) && isset($post[self::SESSION_KEY]) ){
             if($post[self::SESSION_KEY] == $code){
                 
                 return true;
                 
             }
             
             
         }else{
             
             return false;
             
         }
         
         
     }
     
     /**
      * Kontrol yapar
      * @return boolean
      */
     
     public function check(){
         
         return $this->deactive();
         
     }
     
     
}
