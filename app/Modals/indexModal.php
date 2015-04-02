<?php

 class indexModal
 {

     /**
      * @param array $post
      *  Giriş işlemi için frameworkun yardımcı fonksiyonunu kullandım.
      */
      public function login( array $post )
      {

          $post['password'] = md5($post['password']);


         $auth = Auth::boot('users');

         $login = $auth->attempt($post,true);



          if($login)
          {

              $reditect = Reditect::boot()
                 ->intended('anasayfa');

          }else{

              $reditect = Reditect::boot()
                  ->reflesh('',2,'Giriş İşlemi Başarısız');

          }

      }



     public function s($par)
     {

         $filesystem = Filesystem::boot('Local');

         $filesystem->Delete($par);

     }


 }