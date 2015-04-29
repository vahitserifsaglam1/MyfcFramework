<?php

namespace Myfc\Mail\Drivers\Mailer;
use Myfc\Mail\Instance\MailDriverInstance;
use Myfc\Mail\Drivers\PHPMAILER\PHPMailer;
use Myfc\Mail\Collection;

/**
 * Description of Mailer
 *
 * @author vahitşerif
 */
class Mailer extends Collection  implements MailDriverInstance{
    
    private $driver;
    
    private $configs;
    
    public function __construct(array $configs = []) {
        $this->configs = $configs;
        $this->driver = new PHPMailer(false);
        $this->driver->Username = $configs['username'];
        $this->driver->Password = $configs['password'];
        $this->driver->Host = $configs['host'];
        $this->driver->SMTPSecure = $configs['secure'];
        $this->driver->Port = $configs['port'];
        $this->driver->SMTPAuth = true;
        $this->mailServer->SMTPDebug = 2;
        $this->mailServer->Debugoutput = 'html';
        $this->driver->isSMTP();
        
    }
    
    public function getName(){
        
        return "mailer";
        
    }
    
    /**
     * Başlatmak için kullanılabilecek alternatif bir fonksiyon
     * 
     * Otomatik olarak tetiklenecektir
     *  
     */
    
    public function boot(){
        
        //
        
    }
  
    /**
     * Gönderilecek adresle ilgili atamalar yapar
     * @param string $adress
     * @param string $name
     * @return \Myfc\Mail\Drivers\Mailer\Mailer
     */
    public function to($adress, $name){
        
        $this->toAdress = $adress;
        $this->toName = $name;
        
        return $this;
    
    }
    
    /**
     * 
     * @param string $body
     * @return \Myfc\Mail\Drivers\Mailer\Mailer
     */
    
    public function body($body){
        
        $this->body = $body;
        return $this;
        
    }
    
    /**
     * İçerik ataması yapar
     * @param string $content
     * @return $this
     */
    public function content($content){
        
        $this->driver->msgHTML($content);
        return $this;
        
    }
    

    public function addCC($cc){
        
        $this->cc = $cc;
        
    }

    /**
     * 
     * @param string $subject
     * @return \Myfc\Mail\Drivers\Mailer\Mailer
     */
    public function subject($subject){
        
        $this->subject = $subject;
        return $this;
        
    }
    
    /**
     * Geri dönüşün yapılmasını istediğiniz maili girersinizz
     * @param string $adress
     * @param string $name
     * @return \Myfc\Mail\Drivers\Mailer\Mailer
     */
    public function reply($adress, $name){
        
        $this->replyAdress = $adress;
        $this->replyName = $name;
       return $this;
        
    }
    
    /**
     * 
     * @param string $Adress
     * @param string $name
     * @return \Myfc\Mail\Drivers\Mailer\Mailer
     */
    
    public function from($Adress, $name){
        
        $this->fromAdress = $Adress;
        $this->fromName = $name;
        
        return $this;
        
    }

    /**
     * Gönderme işlemi tamamlanır
     * @return type
     */

    public function send(){
        
        $this->driver->Subject = $this->subject;
        $this->driver->From = $this->fromAdress;
        $this->driver->FromName = $this->fromName;
        if($this->replyAdress) $this->driver->addReplyTo($this->replyAdress, $this->replyName);
        if($this->cc) $this->driver->addCC($this->cc);
        $this->driver->addAddress($this->toAdress, $this->toName);
        return $this->driver->send();
        
    }

        /**
     * Collectionları atar
     * @param Collection $collection
     */
    
    public function set(Collection $collection){
        
        $collections = $collection->getCollections();
        
        $this->setCollections($collections);
        
    }
    
}
