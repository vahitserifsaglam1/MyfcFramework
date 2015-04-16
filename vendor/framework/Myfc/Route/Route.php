<?php

/*
 * 
 *  Myfc framework route sınıfı
 * 
 * 
 *  Route::get($param, array('before' => 'auth', 'name' => 'name', 'use' => 'controller@method'));
 * 
 *  Route::get($param, array('before' => 'auth', 'name' => 'name', 'use' => callable function));
 * 
 *  Route::group(array('before' => 'auth'))
 * 
 *  Route::filter('auth' => function(){});
 */

namespace Myfc;

use Myfc\Route\RouteCollection;
use Myfc\Support\String\Parser;
use Myfc\Bootstrap;
use Myfc\Http\Response;
use Exception;
/**
 *  Route
 *
 * @author vahitşerif
 */
class Route {
    
    /**
     * collection sınıfını tutar
     * @var type 
     */
    
    private $collection;
    
    /**
     * parser sınıfını tutar
     * @var type 
     */
    
    private $parser;
    
    /**
     * bootstrap sınıfı tutar
     * @var type 
     */
    
    private $bootstrap;
    
    /**
     * Collectionları tutar
     * @var type 
     */
    private $getCollection;
    
    /**
     * girilen url i tutar
     * @var type 
     */
    private $url;
    
    /**
     * Yapılan isteğin methodunu tutar
     * @var type 
     */
    private $method;
    
    /**
     * Yapılan rötalanmalar tutulur
     * @var type 
     */
    private $routed;
    
    public function __construct() {
        
        $this->collection = new RouteCollection();
        $this->parser = new Parser();
        
    }
    
    /**
     * Dinamik olarak collectiondan çağrılması
     * @param type $name
     * @param type $arguments
     * @return type
     */
    public function __call($name, $arguments) {
        
        return call_user_func_array(array($this->collection, $name), $arguments);
        
    }
    
    /**
     * Temel yürütme fonksiyonu
     * @param Bootstrap $bootstrap
     */
    
    public function run(Bootstrap $bootstrap){
        
        // collectionları çağırır
        $this->getCollection = $this->collection->getCollection();
        
        // bootstrap ı atar
        $this->bootstrap = $bootstrap;
        
        // url atamasını yapar
        
        $this->url = $this->bootstrap->get['url'];
        
        // methodu seçer 
        
        $this->method = $this->bootstrap->server->method;
        
        // gruplandırma işlemi başlar
        
        $collection = $this->startGrouping();
        
        // parçalama başlar
        
        $this->startParsing($this->method,$collection->getCollection());
        
   
        
    }
    
    /**
     * 
     *  Gruplandırma işlemi başlar
     * 
     */
    private function startGrouping(){
        
        // grupları çektik
        $group = $this->getCollection['GROUP'];
        
        
        // eğer dizi boş değilse 
        if(count($group)> 0){
            
         // dizi olarak gelen gruplarımızı parçaladık
        foreach($group as $grup){
            
   
            list($pattern, $callback) = $grup;
            
            // filterleme işlemi başladı
            if($return = $this->filterChecker($pattern)){
                
                // eğer filtreden geçerse callback çağrılacak
                $return = $callback();
                return $this->collection;
                
            }
            
        }
            
        }else{
            
            return $this->collection;
            
        }
        
        
    }
    
    /**
     * Filtreleri test eder
     * @param array $pattern
     * @return boolean
     */
    
    private function filterChecker(array $pattern = array()){
        
        $before = (isset($pattern['before'])) ? $this->before($pattern):array();
        
        if($before){
            
            return true;
            
        }else{
            
            return false;
            
        }
        
    }
    
    /**
     * Before deyimini parçalar
     * @param array $before
     * @return type
     */
    private function before(array $pattern ){
        
       $before = $pattern['before'];
       
       $array = array();
       
       if(is_string($before) && strstr($before, "|")){
           
           $array['filters'] = $this->parseFilters($before);
           
       }elseif(is_string($before)){
           
           $array['filters'] = array($before);
           
       }elseif(is_array($before)){
           
           $array['filters'] = $before;
           
       }
       
       
       
       foreach($array['filters'] as $key){
           
           
           $return = $this->runFilter($key, $this->getFilterPatternKey($pattern,$key));
           
           if($return === false){
               
              
               break;
               
           }
           
       }
       
       return $return;
        
    }
    
    
    private function getFilterPatternKey(array $pattern , $key){
        
        return (isset($pattern[$key])) ? (array) $pattern[$key]:array();
        
    }


    /**
     * Filterin callback olayını çalıştırır
     * @param string $key
     * @param array $parametres
     * @return mixed
     */
    private function runFilter($key,array $parametres = array()) {
        
         $filters = $this->collection->getFilters();
         
         $filter = $filters[$key];
         
         return call_user_func_array($filter, $parametres);
         
         
        
    }
    
    /**
     * Kullanılan filtreleri döndürür
     * @param string $filter
     * @return array
     */
    private function parseFilters($filter = ''){
        
        $filter = trim($filter,"|");
        
        if(strstr($filter,"|")){
            
            return explode("|",$filter);
            
        }else{
            
            return array($filter);
            
        }
        
    }
    
    /**
     * Parçalama işlemi başlar
     * @param type $method
     */
    
    private function startParsing($method = '', $collection){
        
        //methoda göre neyde işlem yapacağımızı seçtik
        $selected = $collection[$method];
         
     
        if($selected !== null){
          
            foreach ($selected as $select){
              
               if($this->parser->checkWithUrl($select[0])){
                   
                    $parametes = $this->parser->getParams();
                    $this->callbackParse($select[1], $parametes);
                   
               }
              
             
            }
        
            
        }
        

    }
    
    /**
     * Url kontrol işlemi yapar
     * @param type $param
     * @return type
     */
    
    private function urlCheck($param) {
        
        $this->parser->set($param);
        
        $parse = $this->parser->parse();
        
        if($parse instanceof Parser){
            
            if($parse->checkWithUrl($this->url)){
                return $this->parser->getParams();
                
            }else{
                
                return false;
                
            }
            
        }else{
            
            return false;
            
        }
        
        
    }
    
    /**
     * callback parçalama işlemi yapılır
     * @param mixed $callback
     * @param array $parametres
     * @return mixed|boolean
     */
    
    private function callbackParse($callback, $parametres) {
        
        if(is_array($callback)){
            
            return $this->callbackArrayParse($callback, $parametres);
            
        }elseif(is_string($callback)){
            
            return $this->callbackStringParse($callback,$parametres);
            
        }elseif(is_callable($callback)){
            
            return $this->callbackCallable($callback, $parametres);
            
        }
        else{
            
            return false;
            
        }
        
        
    }
    
    /**
     * Callback string yürütür
     * @param type $callback
     * @param type $parametres
     * @return type
     */
    private function callbackStringParse($callback,$parametres){
        
        return $this->callbackUseControllerRunner($callback, $parametres);
        
    }


    /**
     * Çağrılabilir bir fonksiyonu yürütür
     * @param callable $callback
     * @param array $parametres
     * @return type
     */
    
    private function callbackCallable($callback, $parametres) {
        
        $parametres[] = new Response();
        
        return call_user_func_array($callback, $parametres);
        
    }
    
    /**
     * Eğer callback array girildiyse burası tetiklenir
     * @param array $callback
     * @param array $parametres
     */
    
    private function callbackArrayParse(array $callback, array $parametres){
        
        // devam edilebilirlik
        $contuine = true;
        
        // filter var ise dene başarılı olursa devam et
        if(isset($callback['before'])){
            
            $contuine = $this->filterChecker($callback['before']);
            
        }
        
        if($contuine){
            
            if(isset($callback['name'])){
                
                // routed e ekleme yapar
                $this->routed[] = $callback['name'];
                
            }
            
            if(isset($callback['use'])){
                
                // kullanılacak işlemi parçalar
                $this->callbackUseRunner($callback['use'],$parametres);
                
            }
            
            
        }
        
    }
    
    /**
     * Callbackdeki use değerini yürütür
     * @param mixed $callbackuse
     * @param array $parametres
     * @return type
     */
    
    private function callbackUseRunner($callbackuse, $parametres) {
        
        if(is_string($callbackuse)){
            
            return $this->callbackUseControllerRunner($callbackuse, $parametres);
            
        }elseif(is_callable($callbackuse)){
            
            return $this->callbackCallable($callbackuse, $parametres);
            
        }
        
    }
    
    private function callbackUseControllerRunner($callbackuse, $parametres){
        
        list($controller, $method) = $this->useControllerParse($callbackuse);
     
        $controller =  $this->bootstrap->make('controller@'.$controller, array(), true);
        
        if(is_callable(array($controller,$method)) || method_exists($controller, $method)){
            
            return call_user_func_array(array($controller,$method), $parametres);
            
        }else{
            
            throw new Exception( sprintf(" %s methodu %s sınıfında bulunamadı",$method,$controller));
            
        }
        
    }
    
    /**
     * Method ve controller a paylaştırır
     * @param string $callback
     * @return array
     */
    
    private function useControllerParse($callback){
        
        if(!strstr($callback, "@")){
            
            $callback = $callback."@boot";
            
        }
        
        return explode("@",$callback);
        
    }
    
    
   
   
    
    
}
