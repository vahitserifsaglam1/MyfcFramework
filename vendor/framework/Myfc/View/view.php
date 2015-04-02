<?php
 
 namespace Myfc;
 
 use Myfc\Template\Engine;
 use Myfc\Singleton;

 class View

 {

     public static $js = array();
     public static $css = array();
     public static $template = array();
     public static $files = array();
     protected static $templateInstalled = false;
     public static $templateArray;
     public static $lang;

     public static function setTemplateArrays($array,$file)
     {
         self::$templateArray[$file] = $array;
         self::templateInstall();

     }
     
     public static function templateInstall()
     {
         if(!self::$templateInstalled)
         {
             Engine::Installer();
             self::$templateInstalled = true;
         }
         foreach(self::$templateArray as $key => $value)
         {


             self::templateLoader(array(),$key,$value);


         }

     }
     
     private static function installLanguage()
     {
         
         static::$lang = Singleton::make('\Myfc\Language');
         
     }
     /**
      * 
      * @param unknown $path -> View dosyasýnýn adý .php olmadan
      * @param array $params -> içeri enjeckte edilecek parametreler
      * @param string $rendefiles ->css, js, template, languea ayarlamalarý yapýlan yer
      * @param array $templateArray -> template in ayarlarý ve tanýmlamalarý
      * @return NULL
      */
     public static function render($path,array $params = array(),$rendefiles = '', array $templateArray = array())
     {

         
         static::installLanguage();
         if(isset($params) && !empty($params))
         {
             $params = $params;
         }
         if(is_array($rendefiles))
         {
             $lang  = $rendefiles['language'];
             
            
             unset($rendefiles['language']);
             $rende = self::renderFiles($rendefiles);
           
             $lang = static::createLanguage($lang);
             

         }else{
             $rende = array();
         }
         
        
         
         $extra = array_merge($params,$rende);
         
         if(isset($lang) && is_array($lang))
         {
             
             $extra = array_merge($extra,$lang);
             
         }
           

         extract($extra);

         ob_start();

         if( isset($files) && is_string($files) )
         {
             file_put_contents($path,$files);
         }
         if(isset($rendefiles['templates'])) $templates = $rendefiles['templates'];
         
         

         if(isset($templates))
         {

             Engine::Installer();
             self::$templateInstalled = true;
             if( is_array($templates) )
             {

                 foreach($templates as $tfiles)
                 {

                     Engine::templateInstaller(array(),$templateArray,$tfiles);

                 }

             }
         }

         if(!strstr($path,'.php'))
         {
             $path = VIEW_PATH.$path.'.php';
         }
         
         if(file_exists($path))
         {
             
             include $path;
             
         }else{
             
             throw new \Exception( sprintf("%s view dosyasý bulunamadý", $path));
             
         }

         

         return null;
     }
     public static function templateLoader($options = array(),$file,$arrays)
     {
         Engine::templateInstaller($options,$arrays,$file);
     }
     public static function renderFiles(array $filess = array())
     {
        
         $files = array(
             'css' => array(),
             'js' => array(),
             'files' => array(),
             'language' => array()
         );

         foreach($filess as $key => $value)
         {

             foreach ( $value as $k )
             {

                 $files[$key][] = $k;
             }
         }

         return self::createHead($files);
     }

     public static function createHead($files)
     {


         if(isset($files['css']))self::$css = self::createCss($files['css']);
         if(isset($files['js'])) self::$js = self::createJs($files['js']);
         if(isset($files['files']) )self::$files = self::createFiles($files['files']);


         
         $return =  array(
             'css' => self::$css,
             'js' => self::$js,
             'files' => self::$files,
         );

         return $return;

     }
     public static function createFiles($files)
     {

         $s = '<?php ';

         foreach($files as $file)
         {
             $s .= 'include "'.PUBLIC_PATH."files/".$file.'";';
         }
         $s .= '?>';
         return $s;
     }
     public static function createCss($files)
     {
         $s = '';
         foreach($files as $key)
         {
             $s .= '<link type="text/css" href="'.PUBLIC_PATH.'css/'.$key.'"/>'."\n";
         }

         return $s;
     }
     public static function createJs($files)
     {
         $s = '';
         foreach($files as $key)
         {
             $s .= '<script type="text/javascript" href="'.PUBLIC_PATH.'js/'.$key.'" /></script>'."\n";
         }
         return $s;
     }
     
     public static function createLanguage(array $language)
     {
         
    
         
         $array = [];
         
         foreach($language as $k => $s)
         {
             
             foreach($s as $v)
             {
                 
                 $array = array_merge($array, static::$lang->rende($k,$v));
                 
             }
             
         }
         
         return $array;
         
     }


 }