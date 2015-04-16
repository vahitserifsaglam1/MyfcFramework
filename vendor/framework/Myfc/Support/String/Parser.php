<?php

/*
 * 
 *  MyfcFramework String parser sınıfı
 *  Girilen değeri parse ederek değerleri döndürür
 * 
 */

namespace Myfc\Support\String;

/**
 * Parser
 *
 * @author vahitşerif
 */
class Parser {

     private $string;
     
     private $parseWith = "#\{(.*?)\}#";
     
     private $explodeWith = "/";
     
     private $implodeWith = "/";
     
     private $params = array();
     
     private $paramsWithoutNulls = array();
     
     private $implodedParams;
     
     private $explodedAction;
     
     private $explodeKey = "{";
     
     private $finds;
    
     /**
      * Başlatıcı fonksiyon
      * Sınıfın paylaştıracağı string atamasını yapar
      * @param string $string
      */
     public function __construct($string = '') {
         $this->string = $string;
     }
     
     /**
      * String atamasını yapar
      * @param string $string
      * @return \Myfc\Support\String\Parser
      */
     public function set($string){
         
         $this->string = $string;
         return $this;
         
     }
     
     /**
      * Explode with ataması yapar
      * @param string $param explode with yerine kullanılacak değer
      * @return \Myfc\Support\String\Parser
      */
     public function explodeWith($param) {
      
          $this->explodeWith = $param;
          return $this;
         
     }
     
     /**
      * sınama işlemi sırasında aranacak anahtar 
      * @param string $key
      * @return \Myfc\Support\String\Parser
      */
     public function explodeKey($key = "{"){
         
         $this->explodeKey = $key;
         return $this;
         
     }
          /**
      * Parçalanacak parse yi ayarlar
      * @param string $pattern
      * @return \Myfc\Support\String\Parser
      */
     public function parseWith($pattern = ''){
         
         $this->parseWith = $pattern;
         return $this;
         
     }
     
     /**
      * Impode işlemini yapacak string
      * @param string $imploder
      * @return \Myfc\Support\String\Parser
      */
     public function impoldeWith($imploder = '/'){
         
         $this->implodeWith = $imploder;
         return $this;
         
     }

      /**
       * Parçalama işleminin yapıldığı fonksiyon
       * @return \Myfc\Support\String\Parser
       */
     
     public function parse(){
         
          $action = trim($this->string,$this->explodeWith);
          
           $actionExploded = explode($this->explodeWith, $action);
           
           $this->explodedAction = $actionExploded;
          
          if(strstr($action, $this->explodeKey)){
              
              
              if($params = $this->createParams($actionExploded)){
                  
                  $this->params = $params[1];
                  
                  $this->paramsWithoutNulls = $this->cleanParamsNulls($this->params);
                  
              }
              
              
          }else{
              
              $this->params = array();
              $this->paramsWithoutNulls = array();
              
          }
          
          return $this;
         
     }
     
     /**
      * Parametreleri bulur
      * @param array $explode
      * @return array|boolean
      */
     
     private function createParams(array $explode){
         
          $params = array();
         
          preg_match_all($this->parseWith, $this->string, $finded);
          
    
          if(count($finded)>0){
              
              return $finded;
              
          }else{
              
              return false;
              
          }
          
         
     }
     
     /**
      * Parametrelerden null olanları temizler
      * @param array $params
      * @return array
      */
     private function cleanParamsNulls(array $params){
         
         $array = array();
         
         foreach($params as $key ){
             
             if($key !== null){
                 
                 $array[] = $key;
                 
             }
             
         }
         
         return $array;
         
     }
     
     /**
      * Parametreleri birleştirir
      * @return \Myfc\Support\String\Parser
      */
     
     public function impodeParams(){
         
       $this->implodedParams = implode($this->implodeWith, $this->params);
       return $this;
         
     }
     
     /**
      * Girilen url ile karşılaştırma yapar, doğruysa sınıf döner eğer değilse false döner
      * @param string $url
      * @return boolean|\Myfc\Support\String\Parser
      */
     
     public function checkWithUrl($url){
         
         $unsetParams = array();
         $p = array();
         $array = array();
         
         // parçalanmış actionu getirir
         $explode = $this->explodedAction;
         
         // parametreleri getirir
         $params = $this->params;
         
         // standart url
         $url = trim($url, $this->explodeWith);
         
         // parçalanmış url
         $urlExploded = explode($this->explodeWith, $url);

         for($i = 0;$i<count($explode);$i++){
             
             $ex = $explode[$i];
          
       
             if(strstr($ex,$this->explodeKey)){
                 
                    $baslangic = strpos($ex,"{");
            
                  
                    $baslangicS = substr($ex,0,$baslangic);
                    
                    if(isset($urlExploded[$i])){
                        
                       
                        $param = substr($urlExploded[$i],$baslangic,strlen($urlExploded[$i]));
                          
                        preg_match($this->parseWith, $ex,$finds);


                        $p[$finds[1]] = $param;
                        
           
                          if(!strstr($ex,$this->explodeKey."!")){

                       
                          $unsetParams[] = $param;

                          }
                          
                            $metin = $baslangicS.$param;
                        
                    }else{ $metin = ""; echo "burda4";}
     
             }else{
               $metin = $ex;
             

           }
          
           $array[] = $metin;
           
         }
         
         $this->params = $p;
         
         $actionImpodedString = implode($this->implodeWith, $array);
         
         
         $urlActionEsitString = substr($url, 0,strlen($actionImpodedString));
         
         if($actionImpodedString == $urlActionEsitString){
           
             $this->paramsWithoutNulls = $this->cleanParamsNulls($this->params);
             return $this;
             
         }else{
             
             return false;
             
         }
         
     }
     
     /**
      * When işlemi için patterne uygun url yapası arar
      * @param string $string  Girilecek action 
      * @param string $url Girilecek url
      * @param string $pattern Girilecek pattern
      * @return string|boolean
      */
     public function when($string = '', $url = '', $pattern = "*"){
         
         if(strstr($string, $pattern)){
          
             $position = strpos($string, $pattern);
             
             $checkString = substr($string,0, $position-1);
             
             $checkUrl = substr($url,0, $position-1);
             
             
         }else{
             
             
             $checkString = $string;
             $checkUrl = substr($url,0, strlen($checkString));
            
             
         }
         
         if($checkString == $checkUrl){
             
             return $url;
             
         }else{
             
             return false;
             
         }
         
     }
     
    public function getFinded(){
        
        return $this->finds;
        
    }

     /**
      * 
      * Parametreleri döndürür
      * @return array
      */
     public function getParams(){
         
         return $this->params;
         
     }
     
     /**
      * Parametre değeri null olmayanları döndürür
      * @return array
      */
     public function getParamsWithoutNulls(){
         
         return $this->paramsWithoutNulls;
         
     }
     
     
     
}
