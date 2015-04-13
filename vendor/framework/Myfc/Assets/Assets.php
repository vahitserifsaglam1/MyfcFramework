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
       *   Ayarlar� atama yapar
       * 
       * @return mixed
       */

      public function setConfigs( array  $configs = array() )
      {
          
           $this->configs = $configs;
           
      }

      /**
       * 
       *  Post verilerini D�nd�r�r
       * 
       * @return mixed
       */
      
      public function returnPost()
      {

          if($_POST === $this->post){


              return $this->post;

          }else{

              return $_POST;

          }
          
      }

      /**
       *  
       *  Get verilerini d�nd�r�r
       * 
       * @return mixed
       */
      public function returnGet()
      {

          if($_GET === $this->get){

              return $this->get;

          }else{

              return $_GET;

          }


      }

      /**
       * 
       * 
       * Gump s�n�f�n� kullanarak xss a����n� kapat�r
       * 
       * 
       * @param array $params
       */
      
      public function xss_clean( array $params )
      {

           return GUMP::xss_clean($params);

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
       * get den url indisini çıkartıp döndürür
       * @return array
       */

      public function returnGetWithoutUrl()
      {

          $get = $this->get;

          if(isset($this->get['url'])){

              unset($get['url']);


          }

          return $get;

      }

      /**
       * Get verisinden değeri boş olanları silerek döndürür
       * @return array
       */
      public function returnGetWithoutNulls(){


          $array = array();

          foreach($this->get as $key => $value){

              if($value !== ""){

                  $array[$key] = $value;

              }

          }

          return $array;

      }


      /**
       * Getden boş varmı kontrol eder
       * @return bool
       */
      public function checkGetForNulls(){


          $var = false;

          foreach($this->get as $key => $value){

              if($value === ""){

                  $var = true;
                  break;

              }

          }

          return $var;

      }

      /**
       * Post da boş varmı kontrol eder
       * @return bool
       */
      public function checkPostForNulls(){

          $var = false;

          foreach($this->post as $key => $value){

              if($value === ""){

                  $var = true;
                  break;

              }

          }

          return $var;

      }

      /**
       * @return mixed
       */
      public function returnRequest()
      {
          $returns = array();
          if($this->post) $returns['POST'] = $this->post;
          if($this->get) $returns['GET'] = $this->get;
          return $returns;
      }
      
      
  }