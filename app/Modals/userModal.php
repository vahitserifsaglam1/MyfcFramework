<?php

 class userModal{


      public function login( array $post = [] )
      {



          $username = $post['username'];

          $password = md5($post['password']);


          $login = Auth::boot('user')
              ->attempt(['username' => $username,'password' => $password],false, function($reditect)
              {

                  $reditect->location('profile');

              });

      }

     public function register( array $post = [] )
     {

         $username = $post['username'];

         $auth = Auth::boot('user');

         $check = $auth->attempt(['username' => $username] );

         if( !$check )
         {

             $kontrol = $auth->register($post);

             if($kontrol)
             {

                 Reditect::boot()
                     ->intended('index');

             }

         }



     }

     public function forget( array $post = [])
     {


         $to = $post['eposta'];

         $mail = Mailer::send(array(
             ''
         ),function($message) use($to){

            return $message->to($to,'MyfcFramework','Şifrenizimi Unuttunuz ?','Unutuma bağlantınız:')
                ->send();

         });

         var_dump($mail);


     }


 }