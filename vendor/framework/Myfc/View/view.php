<?php
 
 namespace Myfc;
 
 use Myfc\Template\Engine;
 use Myfc\Singleton;

 class View

 {

     /**
      * 
      * @var array
      */
     public static $js = array();
     
     /**
      * 
      * @var array
      */
     public static $css = array();
     
     /**
      * 
      * @var array
      */
     public static $template = array();
     
     /**
      * 
      * @var array
      */
     
     public static $files = array();
     
     /**
      * 
      * @var boolean
      */
     protected static $templateInstalled = false;
     
     /**
      * 
      * @var array
      */
     public static $templateArray;
     
     public static $lang;
     
     /**
      *  
      *   Template için barýndýrýlan $templateArray için $file deðiþkenine göre atama yapar
      * 
      * @param array $array
      * @param string $file
      */

     public static function setTemplateArrays(array $array ,$file = '')
     {
         self::$templateArray[$file] = $array;
         self::templateInstall();

     }
     
     /**
      * 
      *  Template kurulumu yapýlýr
      * 
      */
     
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
     
     /**
      * 
      * Language sýnýfý yüklenir
      * 
      */
     
     private static function installLanguage()
     {
         
         static::$lang = Singleton::make('\Myfc\Language');
         
     }
     /**
      * 
      * @param string $path -> View dosyasýnýn adý .php olmadan
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
     
     /**
      * css,js,files,language dosyalarý parçalanmaya baþlanýr
      * @param array $filess
      * @return multitype:multitype:
      */
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

     /**
      * <head /head> taglarý için css,js,files kodlarý oluþturulur
      * @param array $files
      * @return multitype:multitype:
      */
     
     private static function createHead(array $files)
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
     
     /**
      * createHead fonksiyonuna döndürülmek üzere files deðiþkeni oluþtururlur
      * @param array $files
      * @return string
      */
     private static function createFiles(array $files)
     {

         $s = '<?php ';

         foreach($files as $file)
         {
             $s .= 'include "'.PUBLIC_PATH."files/".$file.'";';
         }
         $s .= '?>';
         return $s;
     }
     
     /**
      * 
      * createHead fonksiyonuna döndürülmek üzere css deðiþkeni oluþtururlur
      * 
      * @param array $files
      * @return string
      */
     private static function createCss(array $files)
     {
         $s = '';
         foreach($files as $key)
         {
             $s .= '<link type="text/css" href="'.PUBLIC_PATH.'css/'.$key.'"/>'."\n";
         }

         return $s;
     }
     
     /**
      *
      * createHead fonksiyonuna döndürülmek üzere js deðiþkeni oluþtururlur
      *
      * @param array $files
      * @return string
      */
     private static function createJs($files)
     {
         $s = '';
         foreach($files as $key)
         {
             $s .= '<script type="text/javascript" href="'.PUBLIC_PATH.'js/'.$key.'" /></script>'."\n";
         }
         return $s;
     }
     
     /**
      *
      * createHead fonksiyonuna döndürülmek üzere language deðiþkeni oluþtururlur
      *
      * @param array $files
      * @return array
      */
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