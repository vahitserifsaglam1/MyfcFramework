<?php

  /**
   * 
   *  Sabitlerin tan�mlanmas� -> app klas�r�
   *  @var unknown
   */

  define("APP_PATH", "app/");
  
  /**
   *
   *  Sabitlerin tan�mlanmas� -> public klas�r�
   *  @var unknown
   */
  
  define("PUBLIC_PATH","public/");
  
  /**
   *
   *  Sabitlerin tan�mlanmas� -> system klas�r�
   *  @var unknown
   */
  
  define("SYSTEM_PATH","system/");
  
  /**
   *
   *  Sabitlerin tan�mlanmas� -> vendor klas�r�
   *  @var unknown
   */
  
  define("VENDOR_PATH","vendor/");
  
  /**
   * Sabitlerin tan�mlanmas� -> language(dil) klas�r�
   * @var unknown
   */
  
  define("LANGUAGE_PATH", PUBLIC_PATH.'language');
  
  /**
   * Sabitlerin tan�mlanmas� -> view dosyalar�
   * @var unknown
   */
  
  define('VIEW_PATH', APP_PATH.'Views/');
  
  /**
   * Sabitlerin tan�mlanmas�
   * @var unknown
   */
  
  define("REPORTING", E_ALL);


  define("VERSION", 1);
  
  include SYSTEM_PATH.'bootstrap.php';