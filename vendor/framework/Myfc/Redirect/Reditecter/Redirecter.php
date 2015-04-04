<?php 

 namespace Myfc\Redirect;
 
 
 use Myfc\Http\Request;
 
 class Redirecter{
     
     public $request;
     
     private $url;
     
     /**
      * 
      * @param Request $request
      * @param string $url
      */
     
     public function __construct(Request $request, $url = '')
     {
         $this->url  = $url;
         $this->request = $request;
         
     }
     
     
     /**
      * Yönlendirme iþlemi yapan fonksiyon
      * @param string $type
      * @param array $params
      * @return boolean
      */
     
     public function redirect($type = '' ,array $params = array())
     {
         
         switch($type)
         {
             
             case 'location':
                 
                   if($url = $this->urlStatusCheck($params[0]))
                   {
                       
                        header("Location:$url");
                       
                   }else{
                       
                       return false;
                       
                   }
                
                 break;
                 
             case 'refresh':
                 
          
                   if($url = $this->urlStatusCheck($params[0]))
                   {
                       
                        header("Refresh:{$params[1]},url=$url");
                       
                   }else{
                       
                       return false;
                       
                   }
                 
                 break;
                 
             default:
                 
          
                   if($url = $this->urlStatusCheck($params[0]))
                   {
                       
                        header("Location:$url");
                       
                   }else{
                       
                       return false;
                       
                   }
                 
                 break;
             
         }
         
     }
     
     /**
      * Girilen url in status code sinin 200 olup olmadýðýný kontrol eder
      * @param string $url
      * @return Ambigous <boolean, string>
      */
     private function urlStatusCheck($url = '')
     {
         
         $res = $this->request->get($url);
         
         $statusCode =  $res->getStatusCode();
         
         return ($statusCode) ? $url:false;
         
         
     }
     
 }

?>