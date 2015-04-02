<?php
  namespace Myfc\Session;

  class Starter
  {

      public function __construct()
      {
          $session_name = session_name();
          if (session_start()) {
              setcookie($session_name, session_id(), null, '/', null, null, true);
          }
          return null;
      }

      public function getName()
      {
          return __CLASS__;
      }
      public function  boot()
      {
          return $this;
      }

  }