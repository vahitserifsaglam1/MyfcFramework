<?php



 class bolumModal
 {


      public function bilgileriCek( $id = 0)
      {

       return  Database::boot('bolum')->where(array('id' => 1))->read(true,true);


      }

     public function listele()
     {

         $database = Database::boot('bolum');

         $data = $database->getArray(array('fakulte.ilkisim','bolum.id','bolum.isim','bolum.fakulte'))
             ->join(array('INNER JOIN' => array(
         'fakulte','id','fakulte'
        )))->read(true,false);



          return $data;


     }

     public function fakulteCek()
     {

         $database = Database::boot('fakulte');

         $data = $database->read(true,false);

         return $data;

     }

     public function ekle( array $post = array() )
     {

          $database = Database::boot('bolum');
          $data = $database->setArray($post)
              ->create();


          if($data)
          {

              return "Ekleme işlemi başarılı";

          }else{

              return  "Ekleme İşlemi sırasında hata";

          }

     }

     public function duzenle( array $post, $id)
     {

         $database = Database::boot('bolum');

         $data = $database
             ->setArray($post)->where(array('id' => $id))
             ->update();

         if($data)
         {

             return "İşlem Başarılı";


         }else{

             return "Güncelleme Sırasında Hata oluştu";

         }

     }

     public function sil($id = 0)
     {

         if($id !== 0)
         {

             $database =  Database::boot('bolum');

             $data = $database->where(array('id' => $id))
                 ->delete();

             if($data)
             {

                 return "Silme İşlemi Başarılı";

             }else{

                 return "Silme işlemi sırasında hata";

             }


         }

     }

 }