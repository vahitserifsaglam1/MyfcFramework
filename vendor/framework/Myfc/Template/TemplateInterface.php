<?php

/*
 *  MyfcFramework TemplateInterface
 * 
 *  Template sınıfına yapılacak eklentilerde bu interface olmak zorundadır
 */

namespace Myfc\Template;

/**
 *
 * @author vahitşerif
 */
interface TemplateInterface {
   
    /**
     * Parametreleri sınıf atar
     * @param array $parametres
     */
    public function useTemplateParametres(array $parametres);
    
    /**
     * Görüntü dosyasını oluşturur
     * @param string $file
     */
    public function execute($file);
    
    
}
