<?php
 
 namespace Myfc\Html;

  /**
   * **************************************************
   * 
   * 
   *    MyfcFramework --> Form
   *    
   *     
   *    
   * **************************************************
   * 
   * @author vahit�erif
   *
   */

class Form
{
    
    public static $functions;

    /**
     * @param string $name
     * @param string|array $paramatres
     * @param string $type
     * @param bool $return
     * @return string
     */
    public static function open($name = '',$paramatres,$type='POST',$return = false)
    {

        if(is_array($paramatres))
        {
            $rended = static::render($paramatres," ");
            $msg = "<form id='$name' ".$rended." type='$type'>".PHP_EOL;
        }else{
            $msg = "<form id='$name' action='$paramatres' type='$type'>".PHP_EOL;
        }

        if($return) return $msg;else echo $msg;
    }
    
    /**
     * Submit butonunun oluşturulması
     * @param string|array $params
     * @return string
     */
    public static function submit($params)
    {
        if( is_array($params) )
        {
            $params = static::render($params);
            return "<input type='submit' $params >".PHP_EOL;
        }else{
            return "<input type='submit' value='$params' />".PHP_EOL;
        }
    }
    
    /**
     * Form elemanlarından input tun olu�turulmas�
     * @param string $name
     * @param array $params
     * @return string
     */
    public static function input($name = '',$params)
    {
        if(is_array($params))
        {
            $params = static::render($params);
            return  "<input name='$name' $params >".PHP_EOL;
        }else{
            return "<input type='text' name='$name' value='$params' >".PHP_EOL;
        }
    }
    
    /**
     * Form elemanlarından input -> text in oluşturulması
     * @param string $name
     * @param string|array $value
     * @return string
     */
    public static function text($name,$value)
    {

        if(is_array($value))
        {
            $value = static::render($value);
            return "<input type='text' name='$name' $value />".PHP_EOL;
        }else{
            return "<input type='text' name='$name' value='$value' />".PHP_EOL;
        }
    }
    
    /**
     * form elemanlar�ndan textarean ın oluşturulması
     * @param string $name
     * @param string|array $params
     * @param string $value
     */
    public static function textarea($name,$params = array(),$value = "")
    {
        if(is_array($params))
        {
            echo "<textarea name = '$name' $params >$value</textarea>".PHP_EOL;
        }else{
            echo "<textarea name='$name'>$params</textarea>".PHP_EOL;
        }
    }
    
    /**
     * Sınıfa fonksiyon eklemek
     * @param string $name
     * @param callable $return
     */
    public static function makro($name = '',callable $return)
    {
        if(is_callable($return))
        {
            self::$functions[$name] = Closure::bind($return, null, get_class());
        }else{
            error::newError("$name e atadığınız fonksiyon çağrılabilir bir fonksiyon değildir");
        }

    }
    
    /**
     * Formda kullan�lan select elementinin oluşturulması
     * @param string $name
     * @param string|array $params
     * @param array $options
     * @return string
     */
    public static function select($name = '',$params,array $options = array())
    {
        $msg = "";
        if(is_array($params))
        {
            $params = static::render($params);
            $msg.= "<select name='$name' $params >".PHP_EOL;
        }
        else{
            $msg .= "<select name='$name' $params >".PHP_EOL;
        }

        if(is_array($options))
        {
            foreach($options as $key => $value)
            {
                $msg .= "<options value='$key'>$value</options>".PHP_EOL;
            }
        }
        $msg .= "</select>".PHP_EOL;
        return $msg;
    }
    
    /**
     * Form kapatılır
     * @param boolean $return
     * @return string
     */
    
    public static function close($return = false)
    {
        if($return) return "</form>";else echo "<form>";
    }
    
    /**
     * Dinamik olarak fonksiyon oluşturulması
     * @param string $name
     * @param array $parametres
     * @return mixed
     */
    public static function __callStatic($name = '',array $parametres = array() )
    {
        if(isset(self::$functions[$name]))
        {
            return call_user_func_array(self::$functions[$name],$parametres);
        }

    }
    
    /**
     * 
     * @param array $params
     * @param string $son
     * 
     * @return string
     * 
     */
    private static function render(array $params, $son = " ")
    {
        
        $msg = "";


        foreach($params as $key => $value)
        {

         $msg .= "$key = $value".$son;

        }

        return rtrim($msg,$son);
        
    }
}
?>