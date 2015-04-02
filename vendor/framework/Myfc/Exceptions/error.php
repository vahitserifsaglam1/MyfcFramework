
<?php
 
  namespace Myfc;
  
  use Exception as asException;

class Exception extends asException
{
    protected $message;
    protected $code;
    protected $file;
    protected $line;
    protected $template = 'app/Error/Error.php';
    public function __construct($message = '', $code = 0,$file = '',$line = '')
    {
         $this->message = $message;
         $this->code = $code;
         $this->file = $file;
         $this->line = $line;


           include_once APP_PATH.$this->template;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getCode()
    {
        return $this->code;
    }
    public function getFile()
    {
        return $this->file;
    }
    public function getLine()
    {
        return $this->line;
    }
    public function getTemplate()
    {
        return APP_PATH.$this->template;
    }
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}
    ?>