<?php

/*
 * 
 *  Myfctemplate extensionlarda kullanılacak zorunlu interface
 * 
 */

namespace Myfc\Template\MyfcTemplate\Interfaces;

/**
 *
 * @author vahitşerif
 */
interface MyfcTemplateExtensionInterface {
    
    public function getName();
    public function boot();
    
}
