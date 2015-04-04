<?php
/**
 *  ***************************************************
 *
 *   Myfc Framework Template Engine ( Twig )
 *
 *   *****************************************************
 */
namespace Myfc\Template;
/**
 * Class Engine
 * @package Myfc\Template
 */

 use Twig_Autoloader;
 use Twig_Loader_Filesystem;
 use Twig_Environment;
 
 class Engine
 {

     /**
      * @var $loader
      */

     public static $loader;

     /**
      * @return mixed $loader
      */

     public static function Installer()
     {
         Twig_Autoloader::register();
         $loader = new  Twig_Loader_Filesystem(_PUBLIC.'templates');
         self::$loader = $loader;
         return $loader;
     }

     /**
      * @param array $array
      * @param $file
      * @return mixed
      */

     public static function templateInstaller($options = array(),array $array,$file)
     {

         $options[] = [
             'debug' => false,
             'charset' => 'utf-8',
             'cache' => './cache', // Store cached files under cache directory
             'strict_variables' => true,
         ];
         $twig = new Twig_Environment(self::$loader, $options);

         $return =  $twig->render($file,$array);

         echo $return;

         return $return;
     }

 }