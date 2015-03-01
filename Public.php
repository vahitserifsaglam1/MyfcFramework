<?php

  ob_start();

/**
 *  *************************************************
 *
 *   Ozsaframework başlangıç sayfası
 *
 *   Gerekli dosyaların yolları burada ayarlanır
 *
 *  ************************************************
 */
  return  [

     /**
      *  ***************************
      *
      *    Frameworkun kurulduğu dosya
      *
      *  ****************************
      */
          'base' => dirname(__FILE__),

     /**
      *  *********************************
      *
      *   Anasayfa Dosyası
      *
      *  ********************************
      */

 	      'HomePage' => 'index.php',
     /**
      *  *********************************
      *
      *   App Dosyasının yolu
      *
      *  ********************************
      */
 	      'appPath' => 'app/',
     /**
      *  *********************************
      *
      *   Sistem dosyasının yolu
      *
      *  ********************************
      */
 	      'SystemPath' => 'System/',

     /**
      *  ****************************
      *
      *   Public Dosyasının yolu
      *
      *  **********************
      */
         'PublicFiles' => 'Public/'

      ];


?>