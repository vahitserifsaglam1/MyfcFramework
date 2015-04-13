<?php

    namespace Myfc;

    class Cookie{
        
        /**
         * Tüm cookieleri temizler
         */
		
        public static function flush()
        {
			

            foreach($_COOKIE as  $key => $value)
			
            {
				
                static::delete($key);
				
            }
			
        }

        /**
         * cookie ataması yapar, $name değeri zorunludur ve string dir, $time integer girilmelidir
         * @param string $name
         * @param mixed $value
         * @param integer $time
         */
        public static function set($name = '',$value,$time= 3600)
        {
			if(is_string($value)){
                setcookie($name,$value,time()+$time);
            }

            if(is_array($value)){

                foreach($value as $values){

                    setcookie("$name[$values]",$values ,time()+$time);

                }



            }
			
        }
        
        /**
         *  
         *  Girilen $name değerine göre cookie olup olmadığını kontrol eder varsa cookie i d�nd�r�r yoksa false d�ner
         *  
         * @param string $name
         * @return mixed|boolean
         */

        public static function get($name = '')
        {
			
            if(isset($_Cookie[$name])) return $_COOKIE[$name];else return false;
			
        }


        /**
         * 
         *  girilen $name değişkeni varsa silinir yoksa exception olu�tururlur
         * 
         * @param string $name
         * @throws Exception
         */
        public static function delete($name = '')
        {
			
            if(isset($_Cookie[$name])) setcookie($name,'',time()-29556466);else throw new Exception(" $name diye bir cookie bulunamadı ");
			
        }
    }