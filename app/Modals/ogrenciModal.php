<?php



 class ogrenciModal
 {


      public function ekle( array $post = array() )
      {


            $post['kayittarihi'] = date_format(date_create($post['kayittarihi']), "Y-m-d");
            $auth = Auth::boot('ogrenciler');


               $log= $auth->register($post);


           if($log)
           {

                return "Ekleme İşlemi Başarılı";

           }else{

               return  "Ekleme İşlemi Sırasında bir hata oluştu";

           }

      }

     public function sil( $id = 0 )
     {

      $database = Database::boot('ogrenciler');

         $sil = $database->where(array('numara' => $id))
             ->delete();

         if($sil)
         {

             $reditect = Reditect::boot()
                 ->reflesh('ogrenciler/listele',1,"Silme İşlemi Başarılı");

         }

     }

     public function listele()
     {

         $database = Database::boot('ogrenciler');

         $data = $database
             ->getArray(array(
                'numara','ad','soyad','fakulte','kayittarihi','ceptelefonu','fakulte.ilkisim'
             ))
             ->join(array('INNER JOIN' => array(
                 'fakulte','id','fakulte'
             )))
             ->read(true,false);


         return $data;
     }

     public function guncelle( array $post = array(),$id )
     {

        $database = Database::boot('ogrenciler');

         $data = $database->where(array('numara' => $id))
             ->setArray($post)
             ->update();

         if($data)
         {

             return 'Başarıyla Güncellendi';

         }else{

             return 'Güncelleme Sırasında Bir hata oluştur';

         }

     }

    public function fakulteCek()
    {

        $database = Database::boot('fakulte');

        $data = $database->read(true,false);

        return $data;

    }

     public function bolumCek()
     {

         $database = Database::boot('bolum');

         $data = $database->read(true,false);

         return $data;

     }
     public function bilgileriCek($id)
     {

         $database = Database::boot('ogrenciler');


         $data = $database->where(array('numara' => $id))
             ->read(true,true);



         return $data;

     }



 }