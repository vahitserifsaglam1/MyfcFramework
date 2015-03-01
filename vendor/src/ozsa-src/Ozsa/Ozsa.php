<?php

/**
 * Class Ozsa
 *
 *
 *  ***********************
 *
 *
 *  Ozsa Veri depolama sınıfı
 *
 *
 *  *********************************
 *
 *
 */
Class Ozsa
{


    public static function encode($array)
    {
        $ozsa = "#";
        if(is_array($array))
        {
            foreach ($array as $key => $value) {
                $ozsa .= $key."/";
                if(is_array($value))
                {
                    $ozsa .= self::export_array($value);
                }else{
                    $ozsa .= $value;
                }
                $ozsa .= "#";
            }
            $ozsa = rtrim($ozsa,"#");
            return $ozsa;
        }
    }
    public static function export_array($array)
    {

        $ozsa = "";
        if(is_array($array))
        {
            foreach($array as $key => $value)
            {
                $ozsa .= "$key=>";
                if(is_array($value))
                {
                    $ozsa .= self::export_array($value);
                }else{
                    $ozsa .= $value;
                }
                $ozsa .= "#";
            }
        }
        return $ozsa;
    }


    public static function decode($value)
    {
        $d = array();
        $value = ltrim($value,"#");
        $ilkAyir = explode("#",$value);
        $return = array();
        foreach ($ilkAyir as $key) {

            $ikinciAyir = explode("/", $key);
            if(strstr($ikinciAyir[1], "=>"))
            {
                $ucuncuAyir = explode("=>", $ikinciAyir[1]);
                $d[$ucuncuAyir[0]] = $ucuncuAyir[1];
                $return[$ikinciAyir[0]] = $d;
            }else{
                $return[$ikinciAyir[0]] = $ikinciAyir[1];
            }

        }
        return $return;

    }

}
?>