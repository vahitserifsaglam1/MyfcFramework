<?php

 namespace Myfc\Html;
/**
 * Class Add
 *
 *  ******************************
 *
 *   OzsaFramework Add sınıfı : işlev ;
 *
 *   Add::div
 *
 *   Add::p
 *
 *   Add::a
 *
 *   Add dan sonraki fonksiyon ismiyle yeni bir html elementi oluşturur
 */
class Add
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
     *   Örnek Çıktı
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