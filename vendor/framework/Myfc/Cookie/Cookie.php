<?php

    namespace Myfc;

    class Cookie{
        
        /**
         * Tüm cookileri temizler
         */
		
        public static function flush()
        {
			

            foreach($_COOKIE as  $key => $value)
			
            {
				
                static::delete($key);
				
            }
			
        }

        /**
         * cookie atamasý yapar, $name deðeri zorunludur ve string dir, $time integer girilmelidir
         * @param string $name
         * @param mixed $value
         * @param integer $time
         */
        public static function set($name = '',$value,$time= 3600)
        {
			
            setcookie($name,$value,time()+$time);
			
        }
        
        /**
         *  
         *  Girilen $name deðiþkenine göre cookie olup olmadýðýný kontrol eder varsa cookie i döndürür yoksa false döner
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
         *  girilen $name deðiþkeni varsa silinir yoksa exception oluþtururlur 
         * 
         * @param string $name
         * @throws Exception
         */
        public static function delete($name = '')
        {
			
            if(isset($_Cookie[$name])) setcookie($name,'',time()-29556466);else throw new Exception(" $name diye bir cookie bulunamadÄ± ");
			
        }
    }