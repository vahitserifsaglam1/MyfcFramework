<?php
/**
 * Created by PhpStorm.
 * User: vahitşerif
 * Date: 6.4.2015
 * Time: 23:33
 */

namespace Myfc;
use Exception;

class Crypt {

    private $securityKey;

    private $mode = MCRYPT_MODE_ECB;

    private $rand = MCRYPT_RAND;

    private $alogirtym = MCRYPT_RIJNDAEL_256;

    public function __construct(){

        if(!function_exists('mcrypt_create_iv')){

            throw new Exception('sunucunuzda mcrypt desteği bulunamadı');

        }

        $this->securityKeyCreator();

    }

    /**
     * Güvenlik anahtarı oluştrucu
     */

    private function securityKeyCreator(){

        $config = Config::get('Configs');

        if(isset($config['key'])){

            $this->securityKey = $config['key'];

        }else{

            $this->securityKey = md5($config['url']);

        }

    }

    /**
     * Şifrelenmiş Metin oluşturur
     * @param string $value
     * @return string
     */

    public function encode($value = ''){

        if(is_string($value)){

            $iv = mcrypt_create_iv($this->getIvSize(), $this->getRandomizer());

           $base = base64_encode(json_encode($this->payloadCreator($this->encrypt($value, $iv),$iv)));


            return $base;

        }

    }

    /**
     * @param string $value
     * @param $iv
     * @return string
     *
     * Şifrelenmiş metin oluşturur
     */

    private function encrypt( $value = '', $iv){

        $value  = $this->returnCleanAndHexedValue($value);

        return mcrypt_encrypt($this->alogirtym,$this->securityKey, $value,$this->mode, $iv );

    }

    private function payloadCreator($creypted,$iv){


        return array(

            'value' => base64_encode($creypted),
            'iv' => base64_encode($iv),

        );

    }

    /**
     * Temizlenmiş ve hex e döndürülmüş değer oluşturur
     * @param string $value
     * @return string
     */
    private function returnCleanAndHexedValue($value = ''){

        $value = trim($value);


        return $value;

    }

    private  function hexValue($value){

        if(function_exists('bin2hex')){

            return bin2hex($value);

        }else{

            return $value;

        }


    }

    /**
     * Randomizer i döndürür
     * @return int
     */

    private function getRandomizer(){

        if($this->rand){

            return $this->rand;

        }


    }

    /**
     * Iv uzunluğunu Döndürür
     * @return int
     */

    private function getIvSize(){

        return mcrypt_get_iv_size($this->alogirtym, $this->mode);

    }


    public function decode($value = ''){


        if(is_string($value)){

          $payload = $this->parsePayload($value);

           return  $this->decrypt($payload);

        }

    }

    /**
     * @param $value
     * @return array
     *
     * Payloadı parçalamakta kullanılır
     */
    private function parsePayload($value){


        $based = (array) json_decode(base64_decode($value));



        if(isset($based['value']) && isset($based['iv'])){


            return array(

              'value' => base64_decode($based['value']),
                'iv'    => base64_decode($based['iv'])

            );

        }

    }

    /**
     * Metnin şireli halini çözer
     * @param array $payload
     * @return string
     */
    private function decrypt(array $payload){


        $iv = $payload['iv'];

        $value = $payload['value'];


        $value = $this->returnCleanAndDeHexedValue($value);

        return mcrypt_decrypt($this->alogirtym,$this->securityKey, $value,$this->mode, $iv);

    }

    /**
     * @param $value
     * @return string
     * Parametreyi temizler ve hexden kurtarır
     */

     private function returnCleanAndDeHexedValue($value){

         $value = trim($value);


         return $value;

     }

    /**
     * @param $value
     * @return string
     * Parametreyi hex halinden kurtarır
     */
    private function deHexValue($value){


        if(function_exists('hex2bin')){

            return hex2bin($value);

        }

    }



}