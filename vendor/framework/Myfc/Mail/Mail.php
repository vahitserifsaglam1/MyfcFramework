<?php

namespace Myfc;
use Myfc\Config;
use Exception;
use Myfc\Mail\Instance\MailDriverInstance;
class Mail{
    
    /**
     *
     * @var string ayarları tutar 
     */
    private $configs;
    
    /**
     *
     * @var MailDriverInterface
     */
    
    private $driverInstance;
    
    private $driveList = [
        'mailer' => 'Myfc\Mail\Drivers\Mailer',
        'mailgun' => 'Myfc\Mail\Drivers\Mailgun'
    ];
    
    public function __construct() {
       $this->configs = Config::get('mailConfigs') ;
    }

    /**
     * Mail gönderim işlemi yapar
     * @param string $name  bu parametre mailConfigs.php iiçine göre atanmalıdır
     * @param mixed $callback
     * @throws Exception
     */

    public function send($name, $callback){
        
        $configs = $this->parseFromName($name);
        if($driver = $configs['driver']){
            
            if($selected = $this->driveList[$driver]){
                
                 $this->driverInstance = new $selected($configs);
                 
                 
            }else{
                
                throw new Exception(sprintf("%s adlı bir sürücünüz bulunamadı",$driver));
            
            }
            
        }else{
            
            throw new Exception(sprintf("%s içinde bir sürücü belirtilmemiş",$name));
            
        }
        
     return  $this->callback($callback);
        
        
    }
    
    
    public function extension(callable $callback = null, $autoSelect = false){
        
        if($callback !== null){
            
            
            $response =  $callback();
            
            if($callback && $callback instanceof MailDriverInstance){
                
                
                $this->driveList[$callback->getName()] = get_class($callback);
                
                $callback->boot();
                if($autoSelect === true){
                    
                 return   $this->connect($callback->getName());
                    
                 
                }
                
                
            }
            
        }else{
            
            throw new Exception("Eklenti ekleme sırasında bir hata oluştu, çağrılabilir fonksiyon null döndü");
            
        }
        
       return $this;
        
    }
    
    /**
     * Bağlanılan driver ı değiştirir
     * @param type $name
     * @return \Myfc\Mail
     * @throws Exception
     */
    
    public function connect($name){
        
        if(isset($this->driveList[$name])){
            
            $this->driverInstance = new $name();
            
        }else{
            
            throw new Exception(sprintf("%s Adlı eklenti yüklü değil",$name));
        
        }
        
        return $this;
        
        
    }

        /**
     * Yürütme işlemini yapar
     * @param mixed $callback
     * @return mixed
     */
    
    private function callback($callback){
        
        if(is_callable($callback)){
            
            return $this->callableRunner($callback,$this->driverInstance);
            
        }
        
        
        // girilen callback array ise (class,function) olarak kabul edilir ve tetiklenir
        if(is_array($callback)){
            
            return $this->arrayParser($callback);
            
        }
        
        // girilen string ifadesi çağrılabilir bir ifade olarak kabul edilir ve o addaki bir fonksiyon tetiklenir
        if(is_string($callback)){
            
            return $this->callableRunner($callback);
            
        }
        
    }
    
    /**
     * Çağrılabilir fonksiyonu yürüyür
     * @param callable $callback
     * @param array $parametres
     * @return mixed
     */
    
    private function callableRunner( $callback, $parametres = array()){
        
        if(!is_array($parametres)) $parametres = array($parametres);
        
        return call_user_func_array($callback, $parametres);
    }
    
    /**
     * Callback olarak girilen değer bir array ise bu fonksiyondan geçer
     * @param array $array
     * @return mixed
     */
    private function arrayParser(array $array){
        
        list($class, $function) = $array;
        
        $class = Singleton::make($class);
        
        return $this->classRunner($class, $function , array($this->driverInstance));
        
    }
    
    /**
     * 
     * @param object $class
     * @param string $function
     * @param array $parametres
     * @return mixed
     */
    private function classRunner($class, $function, $parametres = array()) {
        
        if(!is_array($parametres)){
            
            $parametres = array($parametres);
            
        }
        
        return call_user_func_array(array($class,$function), $parametres);
        
    }

    /**
     * 
     * @param string $name
     * @return array
     */
    private function parseFromName($name){
        
        if(!strstr($name, ".")){
            
            return $this->configs[$name];
            
        }else{
            
            $objeckt = (object) $this->configs;
            $ex = explode(".", $name);
            $first = $ex[0];
            unset($first);
            $replaced = implode("->", $ex);
            return eval('return $object->'.$replaced.';');
            
        }
        
        
        
    }
    
}

