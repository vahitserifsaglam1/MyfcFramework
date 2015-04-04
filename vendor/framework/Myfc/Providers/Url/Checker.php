<?php
namespace Myfc\Providers\Url;

 use Myfc\Bootstrap;
/**
 *
 * @author vahitþerif
 *        
 */
class Checker
{

    /**
     * 
     *  Url in olup olmadýðýný kontrol eder
     * 
     * @param Bootstrap $bootstrap
     */
    public function __construct(Bootstrap $bootstrap)
    {
        
        $get =  $bootstrap->adapter->assests->returnGet();
        
        if(!isset($get['url']) || $get['url'] == "")
        {
            
            $bootstrap->adapter->assests->setGet(array('url' => 'index'));
            
        }
        
    }
}

?>