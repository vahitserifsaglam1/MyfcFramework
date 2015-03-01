<?php


 class user extends MainController
 {


     public function __construct()
     {

         parent::__construct();
         $this->_modal('userModal');

     }

     public function login()
     {




         $this->_view->load('login',true);

         if( $this->_assets->checkPost() )
         {

            $validate =  Validator::boot()
                 ->validate($this->_assets->returnPost(),'userlogin');

             if( $validate === true )
             {

                 $this->_modal->login($this->_assets->returnPost());

             }




         }

     }

     public function register()
     {
         $this->_view->load('register',true);
         if ( $this->_assets->checkPost() )
         {

             $post = $this->_assets->returnPost();
             $validate = Validator::boot()
                 ->validate($post,'userregister');

             if( $validate === true )
             {

                  $this->_modal->register($post);

             }

         }

     }
     public function forget()
     {

         $this->_view->load('forget',true);

         if( $this->_assets->checkPost() )
         {

              $post = $this->_assets->returnPost();

              $this->_modal->forget($post);

         }



     }





 }