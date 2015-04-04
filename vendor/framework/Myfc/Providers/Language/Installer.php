<?php
namespace Myfc\Providers\Language;

 use Myfc\Bootstrap;
/**
 *
 * @author vahitþerif
 *        
 */
class Installer
{

    /**
     * 
     *  Language sýnýfýnýn kurulabilmesi için sýnýfý baþlatýr
     * 
     * @param Bootstrap $bootstrap
     */
    public function __construct(Bootstrap $bootstrap)
    {
        
        $bootstrap->singleton('\Myfc\Language');
        
    }
}

?>