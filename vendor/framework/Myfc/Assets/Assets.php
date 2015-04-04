<?php
  namespace Myfc;
  
  /**
   * Class Assets
   * @package Myfc
   */

  use GUMP;

  Class Assets
  {
      /**
       * @var $post
       * @var $get
       * @var $files
       * @var $configs
       * @var static $getS
       * @var static $postS
       */
      
      public $post;
      public $get;
      public $files;
      public $configs;
      public static $getS;
      public static $postS;

      /**
       * @param bool $validate
       */
      public function __construct($validate = true)
      {


          if($_FILES)
          {
              $this->files = $_FILES;

          }
          if($_GET && is_array($_GET))
          {

              if($validate) {
                  
                  $this->setGet( $_GET );
                  
              }else{
                  
                  $this->get = $_GET;
                  
              }
          }
          if($_POST)
          {

              if($validate){
                  
                  $this->setPost( $_POST );
                  
              }else{
                  
                  $this->post = $_POST;
                  
              }

          }

          
      }

      /**
       * 
       *   Ayarları atama yapar
       * 
       * @return mixed
       */

      public function setConfigs( array  $configs = array() )
      {
          
           $this->configs = $configs;
           
      }

      /**
       * 
       *  Post verilerini Döndürür
       * 
       * @return mixed
       */
      
      public function returnPost()
      {
          
          return $this->post;
          
      }

      /**
       *  
       *  Get verilerini döndürür
       * 
       * @return mixed
       */
      public function returnGet()
      {
          
          return $this->get;
          
      }

      /**
       * 
       * 
       * Gump sınıfını kullanarak xss açığını kapatır
       * 
       * 
       * @param array $params
       */
      
      public function xss_clean( array $params )
      {

           return GUMP::xss_clean($params);

      }
 
       /**
        *  Get verilerini static olarak çekmeye yarar
        *  
        */
      
       public static function returnGetStatic()
       {
           
           return static::$getS;
           
       }
       
       /**
        * 
        *  Post verilerini static olarak çekmeye yarar
        *  
        */
       
       public static function returnPostStatic()
       {

           return static::$postS;

       }

      /**
       * @param $name
       * @param $types
       * @return bool
       */
      public function returnFiles($name,$types)
      {
         if($this->files[$name]['type'])
         {
             foreach($types as $key)
             {
                 if($this->files[$name]['type'] == $types)
                 {$return[$name] = $types;}
                 if($return) return $this->files[$name];else return false;
             }
         }
      }

      /**
       * @param $post
       * @return $this
       */


      public function checkPost()
      {

          if( $this->post && isset($this->post) && is_array($this->post) )
          {

              return true;

          }else{

              return false;

          }

      }

      public function checkGet()
      {

          if( $this->get && isset($this->get) && is_array($this->get) )
          {

              return true;

          }else{

              return false;

          }

      }
      public function setPost($post)
      {
          $post = $this->xss_clean( (array) $post);
          $_POST = array();
          $_POST[] = $post;
          $this->post = $post;
          static::$postS = $post;

      }

      /**
       * @param $get
       * @return $this
       */
      public function setGet($get)
      {
          $get = $this->xss_clean( (array) $get);
          $_GET = $get;
          $this->get = $get;
          static::$getS = $get;

      }
      public function getName()
      {
          return "assests";
      }

      public function boot()
      {

          return new static( true );
      }

      /**
       * @return mixed
       */
      public function returnRequest()
      {
          if($this->post) $returns['POST'] = $this->post;
          if($this->get) $returns['GET'] = $this->get;
          return $returns;
      }
      
      
  }