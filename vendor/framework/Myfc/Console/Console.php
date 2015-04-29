<?php

namespace Myfc;

use Myfc\Console\Functioner;
use Myfc\Console\Classer;
use Exception;
use Myfc\Facade;
use Myfc\File;

class Console extends File{

     public $argv;

     public $argc;

     private $funcs;

     private $classes;

     const CLASSPARAM = "CLASS";

     const FUNCPARAM = "FUNC";
     
     private $fileName = "Console.php";



    /**
     * Argv ve argc atamaları yapılır
     * @param array $argv
     * @param $argc
     */

     public function __construct(array $argv, $argc){

         parent::boot();
         unset($argv[0]);

         
         $this->argv = $argv;
         $this->argc = $argc;

         $this->funcs   = new Functioner();
         $this->classes = new Classer();
         
         $alias = $this->inc("app/Configs/Configs.php");
         $alias = $alias['alias'];
         Facade::$instance = $alias;
                 


     }

    /**
     * Parçalamaya başlanır
     * @throws Exception
     */

    public function start(){

        $fileName = $this->fileName;
        
        if($this->argc>0){

            $this->in(APP_PATH);
            
            if($this->exists($fileName)){
                
                $this->inc($fileName);
                
            }else{
                
                throw new Exception(sprintf("%s içinde %s dosyanız bulunamadı, lütfen kontrol ediniz",$this->getIniPath(), $fileName));
                
            }
            
            $finded = $this->findFunctionOrClass($this->argv[1]);

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

        $first = $argv[1];

        unset($argv[1]);

        if(strstr($first, "--class:")){

            $first = str_replace("--class:","",$first);

        }elseif(strstr($first,"--function:")){

            $first = str_replace("--function","",$first);

        }

        $array = (array) $first;

        $argv = array_map(function($a){
           if(strstr($a, "--param:")){
               $a = str_replace("--param:", "", $a);
           }
           return $a;
        }, $argv);
        return array_merge($array, $argv);


    }




}