<?php

namespace Myfc;

use Myfc\Console\Functioner;
use Myfc\Console\Classer;
use Myfc\Facade\App;
use Exception;

class Console{

     public $argv;

     public $argc;

     private $funcs;

     private $classes;

     const CLASSPARAM = "CLASS";

     const FUNCPARAM = "FUNC";



    /**
     * Argv ve argc atamaları yapılır
     * @param array $argv
     * @param $argc
     */

     public function __construct(array $argv, $argc){

         unset($argv[0]);

         $this->argv = $argv;
         $this->argc = $argc;

         $this->funcs   = new Functioner();
         $this->classes = new Classer();

     }

    /**
     * Parçalamaya başlanır
     * @throws Exception
     */

    public function start(){

        if($this->argc>0){

            include "app/Console.php";
            
            $finded = $this->findFunctionOrClass($this->argv[0]);

            switch($finded){

                case self::CLASSPARAM:

                       $this->callClass( $this->$argv );

                    break;

                case self::FUNCPARAM:

                       $this->callFunction( $this->argv );

                    break;

            }

        }else{

           throw new Exception("Hiç bir veri girilmemiş");

        }

    }

    /**
     * Kullanabilmek için altyapıya bir sınıf ekler
     * @param type $class
     * @return \Myfc\Console
     */
    public function AddClass($class){
        
        $this->classes->add($class);
        return $this;
        
    }
    
    /**
     * 
     * Sınıfa çağrılabilir bir fonksiyon ekler
     * @param type $name
     * @param \Myfc\callable $call
     * @return \Myfc\Console
     */
    public function AddFunction($name, callable $call){
        
        $this->funcs->add($name, $call);
        return $this;
        
    }

    /**
     * İlk parametreye göre bir sınıftanmı çağrılacağını yoksa bir fonksiyonmu olduğunu buluruz
     * @param $string
     */
    private function findFunctionOrClass( $string ){

        $strScan = $this->stringScanner($string);

        if($strScan !== false){

            return $strScan;

        }else{

              if($this->classes->check($string)){

                  return self::CLASSPARAM;

              }elseif($this->funcs->check($string)){

                  return self::FUNCPARAM;

              }else{

                  throw new Exception(sprintf(" %s böyle bir fonksiyon yada sınıf bulunamadı", $string));

              }

        }

    }

    /**
     *
     * Metni tarayarak aranan şeyi bulmaya çalışır
     * @param $string
     * @return bool|string
     */
    private function stringScanner($string){

        if(strstr($string, "--class:")){


            return self::CLASSPARAM;

        }elseif(strstr($string,"--function:")){

            return self::FUNCPARAM;

        }else{

            return false;

        }

    }

    /**
     * Sınıfların için fonksiyon çağırır
     * @param array $argv
     * @return mixed
     */

    private function callClass(array $argv){


         $params = $this->returnTheCallingParams($argv);

         return $this->classes->call($params);

    }

    /**
     * Fonksiyonların içinden fonksiyon çağırır
     * @param array $argv
     * @return mixed
     */
    private  function callFunction( array $argv ){

        $params = $this->returnTheCallingParams($argv);

        return $this->funcs->call($params);

    }

    /**
     * Parametreleri kullanıma hazırlar
     * @param array $argv
     * @return array
     */
    private function returnTheCallingParams(array $argv){

        $first = $argv[0];

        unset($argv[0]);

        if(strstr($first, "--class:")){

            $first = str_replace("--class:","",$first);

        }elseif(strstr($first,"--function:")){

            $first = str_replace("--function","",$first);

        }

        $array = (array) $first;

        return array_merge($array, $argv);


    }




}