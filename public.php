<?php

  /**
   * 
   *  Sabitlerin tanýmlanmasý -> app klasörü
   *  @var unknown
   */

  define("APP_PATH", "app/");
  
  /**
   *
   *  Sabitlerin tanýmlanmasý -> public klasörü
   *  @var unknown
   */
  
  define("PUBLIC_PATH","public/");
  
  /**
   *
   *  Sabitlerin tanýmlanmasý -> system klasörü
   *  @var unknown
   */
  
  define("SYSTEM_PATH","system/");
  
  /**
   *
   *  Sabitlerin tanýmlanmasý -> vendor klasörü
   *  @var unknown
   */
  
  define("VENDOR_PATH","vendor/");
  
  /**
   * Sabitlerin tanýmlanmasý -> language(dil) klasörü
   * @var unknown
   */
  
  define("LANGUAGE_PATH", PUBLIC_PATH.'language');
  
  /**
   * Sabitlerin tanýmlanmasý -> view dosyalarý
   * @var unknown
   */
  
  define('VIEW_PATH', APP_PATH.'Views/');
  
  /**
   * Sabitlerin tanýmlanmasý
   * @var unknown
   */
  
  define("REPORTING", E_ALL);
  
  include SYSTEM_PATH.'bootstrap.php';