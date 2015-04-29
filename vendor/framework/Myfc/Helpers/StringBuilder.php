<?php



namespace Myfc\Helpers;

/**
 * Trait StringBuilder
 *
 * @author vahitşerif
 */
trait StringBuilder {
    
    /**
     * Database sınıfında query oluşturur
     * @param array $querys
     * @param string $start
     * @param string $orta
     * @param string $end
     * @return string
     */
    
    public function databaseWhereQueryBuilder($querys = [], $start = '',$orta = '', $end = ''){
        
        $string = $start;
        
        foreach($querys as $key => $value){
            
            $string .= "$key $orta $value".$end;
            
        }
        
        return rtrim($string, $end);
        
    }
    
     public function databaseWhereQueryBuilderOnlyKey($querys = [], $start = '', $end = ''){
        
        $string = $start;
        
        foreach($querys as $key){
            
            $string .= $key.$end;
            
        }
     
        return rtrim($string, $end);
        
    }
    
    
    /**
     * Metni girilen başlangıç ve bitiş değerlerinin içine alır
     * @param type $string
     * @param type $start
     * @param type $end
     * @return type
     */
    public function getStringWithStringAndEndStrings($string,$start = "",$end = ""){
        
        return $start.$string.$end;
        
    }
    
    public function getStringWithStringAndEndStringsFromArrayAndReturnArray($array, $start, $end, $type='keyandvalue'){
        
          $return = [];
          
          switch ($type){
              
              case 'key':
                  
              foreach($array as $key => $value){
              
               if(is_string($key)){ $key = $this->getStringWithStringAndEndStrings($key, $start, $end); }
              
                $return[$key] = $value;
         
               }
                  
              break;
              
              case 'value':
                  
              foreach($array as $key => $value){
              
              $return[$key] = $this->getStringWithStringAndEndStrings($value, $start, $end);
              
              } 
                  
                  break;
              
              case 'keyandvalue':
                  
              foreach($array as $key => $value){
              
              if(is_string($key)){ $key = $this->getStringWithStringAndEndStrings($key, $start, $end); }
              
              $return[$key] = $this->getStringWithStringAndEndStrings($value, $start, $end);
              
              }
                  
                  break;
                  
              default:
                  
             foreach($array as $key => $value){
              
              if(is_string($key)){ $key = $this->getStringWithStringAndEndStrings($key, $start, $end); }
              
              $return[$key] = $this->getStringWithStringAndEndStrings($value, $start, $end);
              
             }
                  
                  
                  break;
              
          }
          
          return $return;
        
    }
    
    
    
}
