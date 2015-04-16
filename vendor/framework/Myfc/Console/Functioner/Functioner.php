<?php
/**
 * Created by PhpStorm.
 * User: vahitşerif
 * Date: 14.4.2015
 * Time: 03:12
 */

namespace Myfc\Console;
use Closure;
/**
 *  MYFC FRAMEWORK Functioner Class
 */
class Functioner {
	
    /**
     * Bind edilen verileri tutar
     * @var type 
     */
	private $bind;
	
        /**
         * Başlatma fonksiyonu
         */
	public function __construct(){
		
		
	}
        
        /**
         * Fonksiyonun olup olmadığına bakar
         * @param type $functionName
         * @return type
         */
	
	public function check( $functionName = ''){
		
		if(isset($this->bind[$functionName]))  return true;else return false;
		
		
	}
        
        /**
         * Bir fonksiyon çağırır
         * @param array $argv
         * @return type
         */
	
	public function call( array $argv ){
		
		$functionName = $argv[0];
		
		unset($argv[0]);
		
		return call_user_func_array($this->bind[$functionName], $argv);
		
	}
	
        /**
         * Bir fonksiyon ekler
         * @param type $name
         * @param callable $callback
         * @return \Myfc\Console\Functioner
         */
	public function add($name, callable $callback){
		
		$this->bind[$name] = Closure::bind($callback,null,get_Class());
		return $this;
	}

}