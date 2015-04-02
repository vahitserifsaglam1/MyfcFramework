<?php

 class anasayfaModal{



     public function bilgileriCek()
     {

         $database = Database::boot('ogrenciler');

         $ogrencisayisi = $database->read(true,false)
             ->rowCount();

         $fakultesayisi = $database->reboot('fakulte')->read(true,false)
             ->rowCount();

         $bolumsayisi = $database->reboot('bolum')->read(true,false)
             ->rowCount();

         $kitapsayisi = $database->reboot('kitap')->read(true,false)->rowCount();

         $odunckitapsayisi = $database->where(array('durum' => 'verildi'))->read(true,false)->rowCount();

         $alinankitapsayisi = $database->where(array('durum' => 'alındı'))->read(true,false)->rowCount();

         $bekleniyor = $database->where(array('durum' => 'bekleniyor'))->read(true,false)->rowCount();

         $array = array(

             'ogrenci' => $ogrencisayisi,
             'fakulte' => $fakultesayisi,
             'bolum'   => $bolumsayisi,
             'kitap'   => $kitapsayisi,
             'verildi' => $odunckitapsayisi,
             'alindi'  => $alinankitapsayisi,
             'bekleniyor' => $bekleniyor

         );

         return $array;

     }

 }