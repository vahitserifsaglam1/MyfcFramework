<?php
/**
 * MyfcFramework Captcha oluşturma sınıfı
 *  
 * @package Myfc
 */

namespace Myfc;
use Myfc\Facade\Session;
/**
 * 
 * @author vahit�erif
 * @class Captcha
 *
 */

class Captcha{
	
	/**
	 * Geni�li�i tutar
	 * @var int
	 */
	
	private $width = 200;
	
	/**
	 * Uzunlu�u tutar
	 * @var int
	 */
	
	private $height = 200;
	
   
	/**
	 * 
	 * @var array
	 */
	
	private $font;
	
	/**
	 * 
	 * @var int
	 */
	
	private $min = 1;
	
	/**
	 * 
	 * @var number
	 */
	
	private $max = 9999999;
	
	
	public function __construct($configs = []){
		
		$this->setConfigs($configs);
		
		
	}
	
	/**
	 * Ayar atamas� yapar
	 * @param array  $configs
	 * @return \Myfc\Captcha
	 */
	
	public function setConfigs( array $configs = [] ){
		
	    $this->setWidth($configs['width']);
		$this->setHeight($configs['height']);
	    $this->setFont( (isset($configs['font'])) ? $configs['font']:'HoboStd.otf' ); 
	   
		
	    return $this;
		
	}
	
	/**
	 * Geni�lik atamas� yapar
	 * @param number $width
	 */
	public function setWidth( $width = 200 ){
		
		$this->width = $width;
		return $this;
		
	}
	
	/**
	 * 
	 * @param number $height
	 * @return \Myfc\Captcha
	 */ 
	
	public function setHeight($height = 200){
		
		$this->height = $height;
		return $this;
		
	}
	
	/**
	 * ayarlamalar� yapar
	 * @param String $font
	 * @return \Myfc\Captcha
	 */
	
	public function setFont($font){
		
		$this->font = $font;
		return $this;
	}
	
	/**
	 * 
	 * @param number $max
	 * @return \Myfc\Captcha
	 */
	public function setMax($max = 1){
		
		$this->max = $max;
		return $this;
		
	}
	
	/**
	 * 
	 * @param number $min
	 * @return \Myfc\Captcha
	 */
	
	public function setMin($min = 1){
		
		$this->min = $min;
		return $this;
		
	}
	
	/**
	 * geni�lik de�erlerini d�nd�r�r
	 * @return number
	 */
	
	public function getWidth(){
		
		return $this->width;
		
	}
	
	/**
	 * Font u getirir
	 * @return multitype:String
	 */
	public function getFont(){
		
		return $this->font;
		
	}
	
	/**
	 * metnin ba�lang�� de�erlerini d�nd�r�r
	 * @return number
	 */
	
	public function getMin(){
	
		return $this->min;
	
	}
	
	/**
	 * metnin geni�lik de�erini d�nd�r�r
	 * @return int
	 */
	public function getMax(){
	
		return $this->max;
	
	}
	
	/**
	 * uzunluk de�erini d�nd�r�r
	 * @return number
	 */
	
	public function getHeight(){
		
		return $this->height;
		
	}
	
    /**
     * Captcha olu�turur
     */
	
	public function generate(){
		 

$harfler = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","y","x","w","z");

$rastgeleharf = rand(0,26);

$rastgelesayi = rand($this->getMin(), $this->getMax());

$resimmetni = $harfler[$rastgeleharf].$rastgelesayi;

$resimmetni = md5($resimmetni);

$yeniresimmetni = substr($resimmetni,rand(1,4),rand(7,9));

Session::set('captcha', $yeniresimmetni);


$resim = imagecreatetruecolor($this->getWidth(),$this->getHeight());

$arkaplanrenk = imagecolorallocate($resim, rand(1,255), rand(1,255), rand(1,255));

$fontrenk = imagecolorallocate($resim, rand(1,255), rand(1,255), rand(1,255));


imagefill($resim, 0, 0, $arkaplanrenk);


imagestring($resim,  rand(4,9), rand($this->getWidth()/rand(20,40),$this->getWidth()/rand(5,10)), rand($this->getHeight()/4,22), $yeniresimmetni,$fontrenk);

header("Cache-Control: no-cache");

header("Content-type: image/png");

imagepng($resim);

imagedestroy($resim);

        }

        public function check($string = ''){
            
            ($string === Session::get('captcha')) ? true:false;
            
        }
        
}