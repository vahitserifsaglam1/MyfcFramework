<?php

 class kategoriModal
 {

     public function kategorileriCek()
     {

         $database = Database::boot('kategori');

         $data = $database->read(true,false);

         return $data;

     }

     public function sil($id = 0)
     {

         $database = Database::boot('kategori');

         $data = $database->where(array('id' => $id))
             ->delete();

         if($data)
         {

             return "Silme İşlemi Başarılı";

         }else{

             return "Silme İşlemi Sırasında Hata Oluştu";

         }

     }

     public function duzenle( array $post ,$id)
     {


         $database = Database::boot('kategori');

         $data = $database->where(array('id'  => $id))
             ->setArray($post)
             ->update();

         if($data)
         {

             return "İşlem Başarıyla Tamamlandı";

             Reditect::boot()->intended('kategori/listele',1);

         }else{

             return "İşlem Gerçekleştirilirken Hata oluştu";

         }

     }

     public function ekle( array $post )
     {

         $database = Database::boot('kategori');

         $data = $database->setArray($post)
             ->create();

         if($data)
         {

             return "İşlem Başarıyla Tamamlandı";

         }else{

             return "Ekle İşlemi Sırasında Hata Oluştu";

         }

     }

 }