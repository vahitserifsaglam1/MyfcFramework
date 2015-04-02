<?php

    namespace Myfc;

    class Cookie{
		
        public static function flush()
        {
			

            foreach($_COOKIE as  $key => $value)
			
            {
				
                static::delete($key);
				
            }
			
        }

        public static function set($name,$value,$time=false)
        {
			
            setcookie($name,$value,time()+$time);
			
        }

        public static function get($name)
        {
			
            if(isset($_Cookie[$name])) return $_COOKIE[$name];else return false;
			
        }


        public static function delete($name)
        {
			
            if(isset($_Cookie[$name])) setcookie($name,'',time()-29556466);else throw new Exception(" $name diye bir cookie bulunamadı ");
			
        }
    }