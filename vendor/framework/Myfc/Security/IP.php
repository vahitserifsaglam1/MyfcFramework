<?php

/*
 *  MyfcFramework Ip sınıfı,  izin gerekmeksizin isteyen herkes kullanabilir,
 *  kopyalayabilir, değiştirebilir
 */

namespace Myfc\Security;

use GeoIp2\Database\Reader;

/**
 * IP girilen ip nin geçerli bir ip olup olmadığıı ve konumunu getiriri
 *
 * @author vahitşerif
 */
class IP {
    
    /**
     *
     * IP nin tutulacağı değişken
     * @var type 
     */
    
    private $realIP;
    
    private $ip;
    
    private $result;
    
    private $reader;
    
    private $database = VENDOR_PATH."framework/Myfc/Security/IPDatabases/GeoLite2-Country.mmdb";
    
    /**
     * Başlatıcı fonksiyon, kullanıcının ip sini kaydeder
     */
    public function __construct(){
        
         $this->realIP = $this->findRealIP();
        
         $this->ip =  $this->findIP();
    }
    

    /**
     * Kullanıcının gerçek ip sini bulur
     * @return type
     */
    public function findRealIP(){
        
        	
	if(getenv("HTTP_CLIENT_IP")) {
 		$ip = getenv("HTTP_CLIENT_IP");
 	} elseif(getenv("HTTP_X_FORWARDED_FOR")) {
 		$ip = getenv("HTTP_X_FORWARDED_FOR");
 		if (strstr($ip, ',')) {
 			$tmp = explode (',', $ip);
 			$ip = trim($tmp[0]);
 		}
 	} else {
 	$ip = getenv("REMOTE_ADDR");
 	}
	
        return $ip;
        
    }
    
    /**
     * Kullanıcının ip sini döndürür
     * @return type
     */
    
    public function findIP(){
        
        return (isset($_SERVER['REMOTE_ADDR'])) ?  $_SERVER['REMOTE_ADDR']:false ;
        
    }
    
    /**
     * Kullanıcının gerçek ip ile mi yoksa proxy ile mi girdiğini kontrol eder
     * @return type
     */
    
    public function ipIsReal(){
        
        return ($this->realIP === $this->ip) ? true:false;
        
    }
    /**
     * Kullanıcının konum bilgilerini döndürür
     * @param string $ip
     * @return array
     */
    public function getResult($ip = null){
        
        if($ip === null) $ip = $this->realIP;
         
         $database = $this->database;
         $this->reader = new Reader($database);
         
         $result = $this->reader->country($ip);
         
         if($result){
             
              $this->result = $result;
              return $result;
             
         }else{
             
             return false;
             
         }
         
        
         
    }
    
    public function getIP(){
        
        if($this->ipIsReal()) return $this->ip;else $this->realIP;
        
    }
    
}
