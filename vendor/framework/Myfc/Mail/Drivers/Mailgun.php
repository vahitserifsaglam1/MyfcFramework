<?php

namespace Myfc\Mail\Drivers;
use Myfc\Mail\Instance\MailDriverInstance;
use Myfc\Mail\Collection;
use Mailgun\Mailgun as Driver;
/**
 * Description of Mailgun
 *
 * @author vahitşerif
 */
class Mailgun extends Collection implements MailDriverInstance{
    private $configs;
    private $driver;
    
    
    public function __construct($array = array()) {
        $this->configs = $array;
        $this->driver = new Driver($this->configs['key']);
    }
    
    public function getName() {
        return "mailgun";
    }
    
    public function boot(){
        
        //
        
    }
    
    public function content($content){
        
        $this->content = $content;
        return $this;
    }
    
    /**
     * Mailin gittiği adres
     * @param string $from
     * @return \Myfc\Mail\Drivers\Mailgun
     */
    
    public function from($from){
        
        $this->from = $from;
        return $this;
        
    }
    
    /**
     * Mailin gideceği adres
     * @param string $to
     * @return \Myfc\Mail\Drivers\Mailgun
     */
    
    public function to($to){
        
        $this->toAdress = $to;
        return $this;
        
    }
    
    /**
     * 
     * @param string $subject
     * @return \Myfc\Mail\Drivers\Mailgun
     */
    
    public function subject($subject){
        
        $this->subject = $subject;
        return $this;
        
    }
    
    /**
     * Gönderme işlemi tamamlanır
     * @return mixed
     */
    
    public function send(){
        
        return $this->driver->sendMessage($this->configs['domain'], array(
           
            'to' => $this->toAdress,
            'from' => $this->from,
            'subject' => $this->subject,
            'text'  => $this->content
            
        ));
        
    }

    /**
     * 
     * Collectionları atar
     * @param Collection $collection
     */
    public function set(Collection $collection){
        
        $collections=  $collection->getCollections();
        
        $this->setCollections($collections);
        
    }
    
}
