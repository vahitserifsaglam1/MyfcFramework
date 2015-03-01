<?php

  namespace Ip;

  /**
   * Class Block
   * @package Ip
   *  This class block unwanted ips
   */

  class Block{


          protected $ips;

          protected $decoded;


           public function  __construct( array $ips  = array() )
           {

                $this->ips = $ips;

                $this->decoded = json_decode($this->ips);

           }

      public static function init( array $ips = array() )
      {

          return new static($ips);

      }

      public function boot()
      {

          $bool = $this->checkIp();

          if( !$this->checkIp() )
          {

              \Desing\Single::make('\Http\Response','Ip adresiniz engellenmiştir',404);

          }

      }

      public function writeIps()
      {
          $path = APP_PATH.'Lib/blockip.json';

          $encode = json_encode($this->decoded);

          if( file_exists( $path ) )
          {

              file_put_contents($path ,$encode);

          }else{

              touch($path);

              $this->writeIps();

          }

      }

      public function addBlock( $ip = '')
      {

          $this->decoded[] = $ip;

      }

      public function returnBlockedIps()
      {

          return $this->decoded;

      }

      public function checkIp()
      {

          $ip = \Ip::getIp();

          return ( in_array($ip,$this->decoded ) ) ? true:false;



      }

  }