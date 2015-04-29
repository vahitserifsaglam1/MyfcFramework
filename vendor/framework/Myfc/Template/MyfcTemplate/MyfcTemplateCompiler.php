<?php


namespace Myfc\Template\MyfcTemplate;
use Myfc\Template\MyfcTemplate;
use Myfc\Template\MyfcTemplate\Exceptions\ItemExceptions\ForItemException;
use Myfc\Template\MyfcTemplate\Exceptions\ItemExceptions\EachItemException;
use Exception;
/**
 * MyfcTemplate -> MyfcTemplateCompiler
 * 
 * metinleri parse eder
 *
 * @author vahitşerif
 */
class MyfcTemplateCompiler {
    
    private $patterns = [
        
        'check' => '{{\s(.*)\s}}',
        'onlyString' => '{{\s@([a-zA-Z0-9.]+)\s}}',
        'for' => '{{\sfor\s([a-zA-Z0-9.]+)\sas\s([0-9])..([0-9])\s}}',
        'eachWithValue' => '{{\seach\s@([a-zA-Z.]+)\sto\s([a-zA-Z]+)\s=>\s([a-zA-Z]+)\s}}',
        'eachOnlyKey' => '{{\seach\s@([a-zA-Z.]+)\sto\s([a-zA-Z]+)\s}}',
        'parameter' => '/^{{\s@(.*?)\s}}$/',
        'if' => '{{\sif\s@([a-zA-Z0-9]+)\s(.*)\s([a-zA-Z0-9]+)\s}}',
        'endfor'  => '{{ endfor }}',
        'endeach' => '{{ endeach }}',
        'endif' => '{{ endif }}',
   
        
    ];
    
    /**
     *
     * @var MyfcTemplate 
     */
    private $system;
    
    /**
     *
     * @var string 
     */
    
    private $content;
    
    
    private $parsed;
    /**
     * 
     * @param MyfcTemplate $system
     */
    
    public function __construct(MyfcTemplate $system = null) {
        $this->setSystem($system);
    }
    
    /**
     * 
     * @param MyfcTemplate $system
     * @return \Myfc\Template\MyfcTemplate\MyfcTemplateCompiler
     */
    
    public function setSystem(MyfcTemplate $system){
        
        $this->system = $system;
        return $this;
    }
    
    /**
     * 
     * Parçalama işlemine başlanır
     * @param string $content
     */
    
    public function parse($content = ''){
        
        $this->content = $content;
     
            
            $parsed = $this->startParsing($content);
            
     echo $parsed;
        
    }
    
    private function startParsing( $content = ''){
      
   if(preg_match_all($this->patterns['check'], $content, $find)){
       
      
       if(!$this->parsed){
           $parsed = $find[0];
       }else{
           
           $parsed = $this->parsed;
           
       }
       
   
      foreach($parsed as $parse){
         
         
          if(preg_match($this->patterns['for'], $parse,$finded)){
              
            $metin = $this->readyForParsing($parse, $parsed, $content,'endfor');
             $content =  str_replace($metin, $this->forCompile($finded,$parse,$metin), $content);
           
            
          }
          elseif(preg_match($this->patterns['if'], $parse,$finded)){
              
              $metin = $this->readyForParsing($parse, $parsed, $content,'endif');
              $content =  str_replace($metin, $this->ifCompiler($finded,$parse,$metin), $content);
          
          }
          
          elseif(preg_match($this->patterns['eachWithValue'], $parse,$finded)){
              
            $metin = $this->readyForParsing($parse, $parsed, $content,'endeach');
             $content =  str_replace($metin, $this->eachWithValueCompiler($finded,$parse, $metin), $content);
              
          }
            elseif(preg_match($this->patterns['eachOnlyKey'], $parse,$finded)){
              
         
            $metin = $this->readyForParsing($parse, $parsed, $content,'endeach');
       
            $content =  str_replace($metin, $this->eachOnlyKeyCompiler($finded,$parse, $metin), $content);
          
              
          }elseif($p = preg_match($this->patterns['onlyString'], $parse,$f)){
        
              if($p){
                 
                   $content = str_replace($this->readyParse($parse), $this->getParameterValue($f[1]), $content);
                
              }
              

          }else{
        
             
              if(preg_match($this->patterns['parameter'], $parse,$f)){
                
                 
                  $value =  $this->getParameterValue($f[1]);
                  $content = str_replace($this->readyParse($parse), $value, $content);
                  
              }elseif(strstr($parse,"(")){
              
                  $content = str_replace($this->readyParse($parse), $this->callableFunctionCompiler($parse), $content);
                  
              }
              
             
          }
          
      }
      
   }
   return $content;
   
   }
   
   private function callableFunctionCompiler($parse){
       
 
       if(strstr($parse, "{"))$parse = substr($parse, 1, strlen($parse)-2);
       $parse = str_replace("()", "", $parse);
       $parse = trim($parse);
       if(strstr($parse,"::")){

     
           return call_user_func($parse);
          
           
       }elseif(strstr($parse,".")){
           
           $explode = explode(".", $parse);
           list($class, $function) = $explode;
           
           $class = $this->system->extensionManager->getExtension($class);
           
           if(is_callable(array($class,$function))){
               
               return call_user_func(array($class,$function));
               
           }else{
               
               throw new Exception(sprintf("%s çağrılabilir bir fonksiyon değil",$function));
               
           }
  
       }else{
           
           return $parse();

       }
       
 
   }
   
   private function readyParse($parse){
       
    return '{'.$parse.'}';
       
   }


   /**
    * Parametrenin verilerini alır
    * @param type $param
    * @return type
    */
   private function getParameterValue($param){
      
       if(!strstr($param, "|")) $param .= "|get";
         
       //
           $parsed = explode("|", $param);
           
           list($param , $function) = $parsed;
           //
           if(strstr($param, "@")) $param = $this->readyParse(ltrim($param,"@"));
           //
           if(strstr($param, "{")){
               $param = ltrim ($param,"{");
               $param = rtrim($param,"}");
           }
           //
           if(strstr($param, ".")){
    
           $param = $this->paramParseObjectGenarator($param);
      
           }
           //
           if(!strstr($function, ".")){
               
               $function = "System.".$function;
               
           }
           
           $p = explode(".",$function);
           
           list($class , $functionName) = $p;
           
           $class = $this->system->extensionManager->getExtension($class);
           
           if(is_callable($class,$function)){
               
               $paramVal = $class->$functionName($param);   
               
           }else{
               
               throw new Exception(sprintf("%s çağrılabilir bir fonksiyon değil",$name));
               
           }
          
             
           
   
       
       $value = $paramVal;
       
 
       return $value;

   }
   
   /**
    * Parametrelerden object olanları parçalar
    * @param string $param
    * @return string
    */
   
   private function paramParseObjectGenarator($param){
       
        
        $ex = explode(".", $param);
        $first = $ex[0];
        unset($ex[0]);
        $s = implode("->",$ex);
        $value = $this->system->collector->get($first);
        if(is_object($value)){
          
           $value = eval('return $value->'.$s.';');
           return $value;
            
        }else{
            
            return $first;
            
        }
        
   }
   
      /**
    * For foreach gibi işlemleri hazırları
    * @param type $parse
    * @param type $parsed
    * @param type $content
    * @return type
    */
   private function readyForParsing($parse,$parsed,$content,$type){
         
            $bassira = array_search($parse, $parsed);  
            $pattern = substr($this->patterns[$type], 1, strlen($this->patterns[$type])-2);
            
            $sonsira = array_search($pattern, $parsed);
            
            $m = $parsed[$sonsira];
           
            $baslangic = strpos($content, $parse)-1;$son = strpos($content, $m)+  strlen($m);
            $metin = substr($content, $baslangic, $son-$baslangic+1);
           
          
            unset($parsed[$sonsira]);
            unset($parsed[$bassira]);
            
            $this->parsed = $parsed;
            return $metin; 
       
   }


   /**
    * For metinlerini kullanıma hazırlar
    * @param type $parse
    * @param type $content
    * @return type
    * @throws ForItemException
    */
   
   private function forCompile($finded,$parse,$content) {
       
        $clean = $this->clean($content, $parse, "endfor");
       
        $cont = "";
        list(, $variable, $baslangic,$bitis) = $finded;
        if($bitis>$baslangic){
            $item = "++";
       
            
            for($b = $baslangic;$b < $bitis;$b++ ){
                
                $this->system->collector->addCollection($variable, $b);
                $cont .= $this->startParsing($clean);  
            }
            
        }elseif($baslangic>$bitis){
               for($b = $baslangic;$b > $bitis;$b-- ){
                
                $this->system->collector->addCollection($variable, $b);
                $cont .= $this->startParsing($clean);         
            }
        }else{
            
            throw new ForItemException(sprintf("%s başlangıç değeri ve %s bitiş değeri birbirine eşit olamaz",$baslangic,$bitis));
            die();
            
        }

        
       $this->system->collector->delete($variable);
       return $cont;
       
        
   }

   /**
    * 
    * @param type $content
    * @param type $parse
    * @param type $type
    * @return type
    */
   private function clean($content, $parse, $type){
             
       $clean = str_replace($this->patterns[$type], "", $content);
       $clean=  str_replace($this->readyParse($parse), "", $clean);
       
       return trim($clean);
   }
   /**
    * Each only key işlemi yapar
    * @param type $parse
    * @param type $content
    */
   private function eachOnlyKeyCompiler($parsed,$parse,$content){
       
       $clean = $this->clean($content, $parse, "endeach");
       list(,$items, $key) = $parsed;
      
       $item = $this->system->collector->get($items); 
       $cont = "";
       $item = (array) $item;
       if(is_array($item)){
           
           foreach ($item as $keys){
           
           $this->system->collector->addCollection($key, $keys );
           $cont .= $this->startParsing($clean);
           
            }
           
       }else{
           
           throw new EachItemException(sprintf("%s değeriniz bir dizi değil",$items));
           die();
           
       }
       
       $this->system->collector->delete($key);
       
     return $cont;

       
   }
   
   /**
    * Each işlemi yapar yapar
    * @param type $parse
    * @param type $content
    * @return type
    * @throws EachItemException
    */
    private function eachWithValueCompiler($parsed,$parse,$content){
       $clean = $this->clean($content, $parse, "endeach");
  
       list(,$items, $key, $value) = $find;
      
       $item = $this->system->collector->get($items); 
       $cont = "";
       $item = (array) $item;
       if(is_array($item)){
           
           foreach ($item as $keys => $values){
           
           $this->system->collector->addCollection($value, $values );
           $this->system->collector->addCollection($key, $keys);
           $cont .= $this->startParsing($clean);
     
           
            }
           $this->system->collector->delete($key);
           $this->system->collector->delete($value);
       }else{
           
           throw new EachItemException(sprintf("%s değeriniz bir dizi değil",$items));
           die();
           
       }
       
    
       
     return $cont;

       
   }
   
   private function ifCompiler($parsed, $parse, $content){
 
       $clean = $this->clean($content, $parse, "endif");
       list(, $variable, $check, $variabletwo) = $parsed;
       $orjv = $variable;$orjvt = $variabletwo;
       $variable = $this->getParameterValue($variable);
       $variabletwo = $this->getParameterValue($variabletwo);
       if(is_string($variable)) $variable="'$variable'";
       if(is_string($variabletwo)) $variabletwo = "'$variabletwo'";
       $ifeval = "if( $variable $check $variabletwo ){ return true; }";
       $eval = eval($ifeval);
    
       if($eval){
           
         $cont =  $this->startParsing($clean);
           
       }
      
       
       #return $cont;
     
       
   }

    /**
     * Boşlukları temizler
     * @param type $content
     * @return type
     */
    private function trimFromContent($content = ''){
        
       
        $exp = explode(" ", $content);
        
        $array = array();
        
        foreach($exp as $key){
            
            if($key !== ''){
                
                $array[] = $key;
                
            }
            
        }
        
        $imp = implode(" ",$array);
        
        return $imp;
        
    }
    
}
