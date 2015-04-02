<?php

 class kitapModal
 {

     public function kategorilerCek()
     {

          $database = Database::boot('kategori');

          $data = $database->read(true,false);

          return $data;

     }

     public function ekle( array $post = array() )
     {

         $database = Database::boot('kitap');

         $data = $database
             ->setArray($post)
             ->create();


         if($data)
         {

             return "İşlem Başarılı";

         }else{

             return "Ekleme İşlemi Sırasında Hata";

         }



     }

     public function oduncCek( $tip )
     {

         $database = Database::boot('odunc');

         $data = $database
             ->where(array('odunc.durum' => $tip))
             ->getArray(array('kitap.kitapkodu','odunc.id','kitap.adi','odunc.alinma','odunc.verilme','odunc.ogrenci','odunc.kitap'))
             ->join(array('INNER JOIN' => array(
         'kitap','kitapkodu','kitap'
     )))
             ->read(true,false);

	



          return $data;

     }

     public function ogrenciCek($id)
     {

         $database = Database::boot('ogrenciler');

         $data = $database->where(array('numara' => $id ))
             ->read();

         return $data;

     }

     public function bilgileriCek($id)
     {

         $database = Database::boot('kitap');

         $data = $database
             ->where( array('kitapkodu' => $id))
             ->read(true,true);



         return $data;

     }

     public function listele()
     {

         $database = Database::boot('kitap');
         $data = $database
             ->getArray(array(
                 'kitapkodu','adi','sayfasayisi','isbn','kategori.isim','yazari'
             ))->join( array('INNER JOIN' => array(

                 'kategori','id','kategori'

             )))
             ->read(true,false);

         return $data;

     }

     public function sil( $kitapkodu = 0)
     {

         if($kitapkodu !== 0)
         {

             $database = Database::boot('kitap');


             $data = $database
                 ->where(array('kitapkodu' => $kitapkodu))
                 ->delete();



             if($data)
             {

                 return "Silme İşlemi Başarılı";

             }else{

                 return "Silme İşlemi Sırasında Hata";

             }

         }

     }

     public function duzenle( array $post = array(), $kitapkodu)
     {

         $database = Database::boot('kitap');

         $data = $database->where(array('kitapkodu' => $kitapkodu))
             ->setArray($post)
             ->update();

         if($data)
         {

             return "Güncelleme İşlemi Başarılı";

         }else{

             return "Güncelleme İşlemi Hatalı";

         }

     }

     public function ver( array $post = array() )
     {

         $ogrenci = $post['ogrenciID'];
         $kitap = $post['kitapID'];

          $verilmeTarih = date('Y-m-d');

          $database = Database::boot('kitap');


          $data = $database->setArray(array('durum' => 'verildi'))
              ->where(array('id' => $kitap))
              ->update();

          $veri = $database->reboot('odunc')
              ->setArray(array(
                   'kitap' => $kitap,
                   'ogrenci' => $ogrenci,
                   'verilme' => $verilmeTarih,
                   'durum' => 'verildi'
              ))
              ->create();



           return "İşlem Başarılı";




     }




     public function kitapCek($kitapkodu = 0)
     {

         $database = Database::boot('kitap');

         $data = $database->where(array('kitapkodu' => $kitapkodu))
             ->join(array(

                 'INNER JOIN' => array(
                     'kategori','id','kategori'
                 )

             ))
             ->read(true,true);


         return $data;

     }

     public function ogrencilerCek()
     {

         $database = Database::boot('ogrenciler');

         $data = $database
             ->join(array(
                 'INNER JOIN' => array(
                     'bolum','id','bolum'
                 )))
                 ->getArray(array('bolum.isim','ogrenciler.fakulte','ogrenciler.kayittarihi','ogrenciler.ceptelefonu','ogrenciler.ad','ogrenciler.soyad','ogrenciler.numara'))

             ->read(true,false);


         return  $data;

     }

     public function fakulteCek($fakulteId)
     {

         $database = Database::boot('fakulte');

         $data = $database->where(array('id' => $fakulteId))
             ->getArray(array('isim'))
             ->read();

         return $data;
     }

     public function oduncsayisicek($ogrenciId)
     {

         $database = Database::boot('odunc');

         $data = $database->where(array('ogrenci' => $ogrenciId))
             ->read(true,false)->rowCount();

         return $data;

     }




     public function kitaplarCek()
     {

         $database = Database::boot('kitap');

         $data = $database->
         join(array(

             'INNER JOIN' => array(

                 'kategori','id','kategori'

             )

         ))->
         read(true,false);



         return $data;

     }

     public function teslimal($kitapKodu, $oduncID)
     {


         $alinma = date('Y-m-d');

         $database = Database::boot('odunc');

         $data = $database->where(array('id' =>  $oduncID))
             ->setArray(array('durum' => 'alindi'))
             ->update();

         $id = $database->reboot('kitap')
             ->addGet('id')
             ->where(array('kitapkodu' => $kitapKodu))
              ->read()
              ->id;

         $database->reboot('odunc');

         $dataOdunc = $database
             ->where(array('kitap' => $id
             ,'id' => $oduncID
                 ))

             ->setArray(array('alinma' => $alinma))
             ->update();


         if($dataOdunc && $data)
         {

             return "İşlem Başarılı";

         }else{

             return "İşlem Hatalı";

         }


     }





 }