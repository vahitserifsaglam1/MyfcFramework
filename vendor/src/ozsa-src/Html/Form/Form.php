<?php

namespace Html;

class Form
{
    public static function open($name,$paramatres,$type='POST',$return = false)
    {

        if(is_array($paramatres))
        {
            $rended = render($paramatres," ");
            $msg = "<form id='$name' ".$rended." type='$type'>".PHP_EOL;
        }else{
            $msg = "<form id='$name' action='$paramatres' type='$type'>".PHP_EOL;
        }

        if($return) return $msg;else echo $msg;
    }
    public static function submit($params)
    {
        if( is_array($params) )
        {
            $params = render($params);
            return "<input type='submit' $params >".PHP_EOL;
        }else{
            return "<input type='submit' value='$params' />".PHP_EOL;
        }
    }
    public static function input($name,$params)
    {
        if(is_array($params))
        {
            $params = render($params);
            return  "<input name='$name' $params >".PHP_EOL;
        }else{
            return "<input type='text' name='$name' value='$params' >".PHP_EOL;
        }
    }
    public static function text($name,$value)
    {

        if(is_array($value))
        {
            $value = render($value);
            return "<input type='text' name='$name' $value />".PHP_EOL;
        }else{
            return "<input type='text' name='$name' value='$value' />".PHP_EOL;
        }
    }
    public static function textarea($name,$params,$value = "")
    {
        if(is_array($params))
        {
            echo "<textarea name = '$name' $params >$value</textarea>".PHP_EOL;
        }else{
            echo "<textarea name='$name'>$params</textarea>".PHP_EOL;
        }
    }
    public static function makro($name,$return)
    {
        if(is_callable($return))
        {
            self::$functions[$name] = Closure::bind($return, null, get_class());
        }else{
            error::newError("$name e atadığınız fonksiyon çağrılabilir bir fonksiyon değildir");
        }

    }
    public static function select($name,$params,$options)
    {
        $msg = "";
        if(is_array($params))
        {
            $params = render($params);
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
    public static function close($return = false)
    {
        if($return) return "</form>";else echo "<form>";
    }
    public static function __callStatic($name,$parametres)
    {
        if(isset(self::$functions[$name]))
        {
            return call_user_func_array(self::$functions[$name],$parametres);
        }

    }
}
?>