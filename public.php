<?php

  /**
   * 
   *  Sabitlerin tanımlanması -> app klasorü
   *  @var unknown
   */

  define("APP_PATH", "app/");
  
  /**
   *
   *  Sabitlerin tanımlanması -> public klasorü
   *  @var unknown
   */
  
  define("PUBLIC_PATH","public/");
  
  /**
   *
   *  Sabitlerin tanımlanması -> system klasorü
   *  @var unknown
   */
  
  define("SYSTEM_PATH","system/");
  
  /**
   *
   *  Sabitlerin tanımlanması -> vendor klasorü
   *  @var unknown
   */
  
  define("VENDOR_PATH","vendor/");
  
  /**
   * Sabitlerin tanımlanması -> language(dil) klasorü
   * @var unknown
   */
  
  define("LANGUAGE_PATH", PUBLIC_PATH.'language');
  
  /**
   * Sabitlerin tanımlanması -> view dosyaları
   * @var unknown
   */
  
  define('VIEW_PATH', APP_PATH.'Views/');
  
  /**
   * Sabitlerin tanımlanması
   * @var unknown
   */
  
  define("REPORTING", E_ALL);


  define("VERSION", 1);
  
  include SYSTEM_PATH.'bootstrap.php';