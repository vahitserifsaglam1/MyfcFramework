<?php
/**
 * Created by PhpStorm.
 * User: vahitşerif
 * Date: 14.4.2015
 * Time: 03:12
 */

namespace Myfc\Console;


class Classer {

    private $bind;

    public function __construct(){


    }
    
    /**
     * Fonksiyonun var olup olmadığına bakar
     * @param type $className
     * @return type
     */

    public function check($className){

     
	   return (isset($this->className($name))) ? true:false;


    }
	
	/**
         * class ekler
         * @param type $class
         * @return \Myfc\Console\Classer
         */
	
	public function add($class)
	{
		if(is_object($class)){
                    $this->bind[$class] = $class;
                }
		return $this;

        }


        /**
         * Fonksiyon çağırır
         * @param array $argv
         * @return type
         */
    public function call(array $argv){

	    $className = $argv[0];
		$methodName = $argv[1];
		
		unset($argv[0]);unset($argv[1]);
		
		$params = $argv;
		
		return call_user_func_array(array($this->bind[$className],$methodName),$params);
    }





}