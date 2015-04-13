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
      * Y�nlendirme işlemi yapan fonksiyon
      * @param string $type
      * @param array $params
      * @return boolean
      */
     
     public function redirect($type = '' ,array $params = array())
     {


         switch($type)
         {
             
             case 'location':
                 
                   if($url = $this->otherPageUrlCheck($this->urlStatusCheck($params[0])))
                   {
                       
                        header("Location:$url");
                       
                   }else{
                       
                       return false;
                       
                   }
                
                 break;
                 
             case 'refresh':
                 
          
                   if($url = $this->otherPageUrlCheck($this->urlStatusCheck($params[0])))
                   {
                       
                        header("Refresh:{$params[1]},url=$url");
                       
                   }else{
                       
                       return false;
                       
                   }
                 
                 break;
                 
             default:


                 if($url = $this->otherPageUrlCheck($this->urlStatusCheck($params[0])))
                   {
                       
                        header("Location:$url");
                       
                   }else{
                       
                       return false;
                       
                   }
                 
                 break;
             
         }
         
     }

     /**
      *  Çağırlan sayfanın dışardan bir sayfa olup olmadığını kontrol eder
      *
      * @param string $url
      * @return string
      */

     private function otherPageUrlCheck($url = '')
     {

         $baslangic = substr($url, 0, 10);

         if(strstr($baslangic, "http://") ||strstr($baslangic, "http://www.") || strstr($baslangic, "www.") || strstr($baslangic, "https://")
           || strstr($baslangic, "https://www"))
         {


             return $url;

         }else{

             return $this->url.$url;

         }

     }

     /**
      * Girilen url in status code sinin 200 olup olmadığını ve
      * @param string $url
      * @return Ambigous <boolean, string>
      */
     private function urlStatusCheck($url = '')
     {


        return $url;
         
         
     }
     
 }

?>