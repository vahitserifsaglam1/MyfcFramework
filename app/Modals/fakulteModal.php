<?php

 class fakulteModal
 {


     public function bilgileriCek()
     {

         $database = Database::boot('fakulte');

         $data = $database->read(true,false);

         return $data;

     }

     public function ekle( array $post = array() )
     {

         $post['ilkisim'] = $post['isim'];
         $database = Database::boot('fakulte');

         $data = $database
             ->setArray($post)
             ->create();


         if($data)
         {

             return "Ekleme İşlemi Başarılı";

         }else{

             return "İşlem Hatalı";

         }

     }

     public function sil( $id = 0)
     {

         $database = Database::boot('fakulte');

         $data = $database->where(array('id' => $id))
             ->delete();

         $bolumsil = $database->reboot('bolum')
             ->where(array('fakulte' => $id))
             ->delete();


         if($data)
         {

           return "Silme İşlemi Başarılı";

         }else{

             return "Silme İşlemi Sırasında Bir Hata Oluştu";

         }

     }



     public function duzenle(array $post = array(), $id = 0)
     {

         $database = Database::boot('fakulte');

         $data = $database->where(array('id' => $id))
             ->setArray($post)
             ->update();

         if($data)
         {

            return "Güncelleme İşlemi Başarılı";

         }else{

             echo "Güncelleme İşlemi Sırasında bir hata oluştu";

         }

     }


     public function bilgiler($id = 0)
     {

         $database = Database::boot('fakulte');
         $data = $database->where(array('id' => $id))
             ->read(true,true);

         return $data;

     }



 }