<?php
/**
 *
 *   Pagination sınıfı ayarlar dosyası
 *
 *
 *    min , max // -> sayıların  başlangıç ve bitişi ,, bulunduğunuz sayfanın min altından başlar ve max  a kadar artırır
 *
 *    eğer min 1 den küçükse değer 1 olarak kabul edilir
 *
 *    homeClass pagination sınıfından oluşacak ana div in class ı dır
 *
 *    linkClass her linkin örn : <a href='index/1' class = "linkClass" >
 *
 *
 *
 */
  return [
      'min' => 15,
      'max' => 100,
      'homeClass' => 'pagi',
      'linkClass' => 'pagination'
      ];