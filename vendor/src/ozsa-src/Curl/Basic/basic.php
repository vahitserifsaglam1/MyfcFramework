<?php

 namespace Curl;
 use Symfony\Component\Validator\Constraints\File;

 /**
  * Class Curl
  * @package Curl\Basic
  *
  *  *********************************
  *
  *  Myfc framework  basit curl sınıfı, static ;
  *
  *
  *  ///////////////////////////////
  *
  *   Curl::init();
  *
  *   Curl::setOpt(OPT_NAME,OPT_VALUE);
  *
  *  Curl::get('http://www.google.com');
  */
 class Basic
 {
     public static $options;

     public static $ch;

     /**
      *
      *  Static olarak sınıfın başlatılması
      *
      *  Curl\Basic::init()
      *
      */
     public static function init()
     {
         self::$ch = curl_init();
         self::$options = array (

             CURLOPT_REFERER => 'http://www.twitter.com',
             CURLOPT_USERAGENT =>  $_SERVER['HTTP_USER_AGENT'],
             CURLOPT_RETURNTRANSFER => 1,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_TIMEOUT => 50

         );
     }

     /**
      * @param $url
      * @return mixed
      */

     public static function get($url)
     {
         $class = get_class();
         if(!self::$ch) $class::init();

         self::setUrl($url);

         curl_setopt_array(self::$ch, self::$options);

         return curl_exec(self::$ch);

     }

     /**
      * @param $url
      * @param array $params
      * @return mixed
      */

     public static function post($url, $params = array() )
     {
         $fields = "";
         $class = get_class();
         if(!self::$ch) $class::init();

         self::setUrl($url);

         self::$options[CURLOPT_POST] = count($params);

         foreach($params  as $key => $value) { $fields .= $key.'='.$value.'&'; } $fields = rtrim($fields, '&');

         self::$options[CURLOPT_POSTFIELDS] = $fields;

         curl_setopt_array(self::$ch,self::$options);

         $ex =  curl_exec(self::$ch);

         return $ex;

     }

     /**
      * @param $url
      * @param string $path
      * @return mixed
      */

     public static function download($url,$path = "downloads")
     {
         if(!self::$ch) Curl::init();

         $filesystem = \Filesystem::boot('local');

          if(!$filesystem->exists($path))
          {

              $filesystem->createDirectory($path);

          }

         $file = fopen($path,"w+");

         self::setUrl($url);

         self::$options[CURLOPT_FILE] = $file;

         curl_setopt_array(self::$ch,self::$options);

         return curl_exec(self::$ch);
     }

     /**
      * @param $params
      */

     public static function addPost($params)
     {
         $fields = "";
         self::$options[CURLOPT_POST] = count($params);

         foreach($params  as $key => $value) { $fields .= $key.'='.$value.'&'; } $fields = rtrim($fields, '&');

         self::$options[CURLOPT_POSTFIELDS] = $fields;
     }

     /**
      * @param $url
      */

     public static function setUrl($url)
     {
         self::$options[CURLOPT_URL] = str_replace(" ","%20",$url);
     }
     /**
      *
      */
     public static function close()
     {
         curl_close(self::$ch);
     }

     /**
      * @param $name
      * @param $params
      */

     public static function __callString($name,$params)
     {
         if($name=="setOpt")
         {
             foreach($params as $key => $value )
             {

                 self::$options[$key] = $value;

             }

         }
     }



 }