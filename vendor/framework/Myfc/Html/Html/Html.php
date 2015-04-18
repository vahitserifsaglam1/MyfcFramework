<?php

 namespace Myfc\Html;
 use Myfc\Config;
/**
 * Class Html
 *
 *  ******************************
 *
 *   Myfcframework Add s�n�f� : i�lev ;
 *
 *   Add::div
 *
 *   Add::p
 *
 *   Add::a
 *
 *   Add dan sonraki fonksiyon ismiyle yeni bir html elementi olu�turur
 */
class Html
{
    

      
    /**
     * @param $name
     * @param $params
     * @return string
     *
     *
     *   $name = div,a,p
     *
     *
     *  
     *
     *  <div class = 'form-class'>$content</div>
     */

    public static function __callStatic($name,$params)
    {
        $msg = "";
        $styles = $params[0];
        $content = $params[1];
        if( is_array($styles) )
        {
            $styles = render($styles, " ");
        }
        if($name != "li" || $name !="option")
        {


            $msg .= "<$name $styles >".PHP_EOL;
            $msg .= $content.PHP_EOL;
            $msg .= "</div>".PHP_EOL;


        }else{
            switch($name){
                case 'li':
                    $msg .= "<li $styles>$content</li>".PHP_EOL;
                    break;
                case 'option':
                    $msg  .= "<option value='$styles'>$content</option>".PHP_EOL;
                    break;
            }
        }
        return $msg;
    }

}
?>