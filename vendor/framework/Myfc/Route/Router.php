<?php
namespace Myfc;
 
 use Myfc\Bootstrap;
 use Myfc\Facade\Event as Events;

 /**
 *
 * @author vahitşerif
 *
 *  ***********************************************
 *
 *  Route işleme sınıfı
 *
 *  ***********************************************
 *
 *  İzin verilen kullanımlar
 *
 *  Route::get($eslesme, $callableFunction())
 *
 *  Route::get($eslesme, "controller@methodname")
 *
 *  Route::get($eslesme, array('HTTPS', $callableFunction())
 *
 *  Route::get($eslesme, array('AJAX', $callableFunction())
 *
 *  Route::get($eslesme, array("controller@methodname", $callableFunction())
 *
 *  Route::get($eslesme, array(array($eventname, $parametres), $callableFunction())
 *
 *  Route::get($eslesme, array("event|eventname", $parametres))
  *
  * Route::get($eslesme, array('controller@method', array($event,$parametres)
 *        
 */
class Router
{

    private $container;
    
    private $url;
    
    private $collection;
    
    private $method;
    
    private $unsetParams;
    
    private $params;
    
    /**
     * Başlatıcı method;
     * @param Bootstrap $bootstrap
     * @param array $collection
     */

    public function __construct(Bootstrap $bootstrap = null, array $collection = array())
    {

         $this->run($bootstrap, $collection);

    }
    
    /**
     * 
     * Route olayını yütür
     * @param Bootstrap $container
     * @param array $collection
     */
    
    public function run(Bootstrap $container, array $collection = array() )
    {


        $this->container = $container;
        
        $this->collection = $collection;
        
        $this->method = $this->container->server->method;
        
        $this->url = $this->container->get['url'];
        
        $this->startParsing();

        
    }
    
    /**
     * Parçalama işlemi başlar
     */
    
    private function startParsing()
    {
        
        
        $this->methodParsing($this->method);
        
        
    }
    
    /**
     * Yap�lan tipe g�re par�alanmaya ba�lan�r
     * @param unknown $method
     */
    
    private function methodParsing( $method )
    
    {
        
        
         $selected = $this->collection[$method];
         
         if($selected !== null )
         {
             
             
             foreach($selected as $select)
             {
                  
                 if($this->actionParsing($select['action'])){
             
                     $this->callbackParsing($select['callback']);
             
                 }
             
                  
             }
             
         }
         
       
        
    }
    
    /**
     * Link kontrolu 
     * @param unknown $action
     * @return unknown
     */
    private function actionParsing($action)
    {

        $urlString = rtrim($this->url,"/");

        $url = explode("/", rtrim($this->url, "/"));


        $action = ltrim(rtrim($action, "/"),"/");

        $explode = explode("/", $action);


        if(strstr($action,"{")){

            

            if($params = $this->urlChecker($urlString,$url,$explode)){

           

                  $this->params = $this->paramsWhereCheck($params['finded']);
                  $this->unsetParams = $this->paramsWhereCheck($params['finded']);


                return true;

            }



        }else{

            if($action === $urlString){

                $this->params = array();
                $this->unsetParams = array();

                return true;

            }

        }




    }

    private function urlChecker($urlString,array $url, array $explode){

        $array = [];
        $params = [];
        $unsetParams = [];
        $finded = [];

       for($i=0;$i<count($explode);$i++){

           $ex = $explode[$i];

           if(strstr($ex,"{")){

               $baslangic = strpos($ex,"{");


               $baslangicS = substr($ex,0,$baslangic);
  

               if(isset($url[$i])){

                 
                   $param = substr($url[$i],$baslangic,strlen($url[$i]));

                   preg_match("#\{(.*?)\}#", $ex,$finds);

                   $finded[$finds[1]] = $param;

                   $params[] = $param;

                   if(!strstr($ex,"{!")){

                       $unsetParams[] = $param;

                   }

                   $metin = $baslangicS.$param;


               }else{
                   
                  $metin = "";
                   
                   
               }

           }else{

               $metin = $ex;

           }

           $array[] = $metin;


       }

        $actionUrlString = implode("/",$array);
       
        $urlActionEsitString = substr($urlString, 0,strlen($actionUrlString));
        

        if($actionUrlString === $urlActionEsitString){

            return [
                'params' => $params,
                'unsetParams' => $unsetParams,
                'finded' => $finded
            ];

        }else{

            return false;

        }

    }



    /**
     * parametrelerde where tanımına uyanların karşılaştırılması
     * @param array $params
     * @return array
     */
    
    private function paramsWhereCheck(array $finded)
    {
        $where = $this->collection['WHERE'][0];
        
        $parametres = array();

        foreach ($finded as $findKey => $findValue){

            if(isset($where[$findKey])){

                preg_match($where[$findKey], $findValue,$o);

                if($o){

                    $parametres[] = $o[1];

                }

            }else{

                $parametres[] = $findValue;

            }

        }
        

         return $parametres;
            

            
        }

     
        


    /**
     * Callback kısmının parçalanması
     * @param $callback
     */
    
    private function callbackParsing($callback)
    {


        // *>eğer çağrılabilir se
        
        if(is_callable($callback))
        {
            
            $this->runCallable($callback);
            
        }
        
       //

        // -> eğer bir dizi ise
        if(is_array($callback))
        {



            if(is_string($callback[0]))
            {

                $this->callBackString($callback);
                
            }
            
            if(is_array($callback[0]))
            {

                $this->callBackArray($callback);
                
            }



            
        }
        
        //
        
        // -> eğer string bir ifade ise
        
        if(is_string($callback))
        {
            
            $this->runController($callback);
            
        }
        
        //
        
        
    }
    
    /**
     * Callback olay�n�n 0. indisi string ise çağrılır
     * @param string $callback
     */
    private function callBackString($callback ='')
    {


        if($callback[0] == "AJAX" || $callback[0] == "HTTPS" || $callback[0] == "https://" )
        {

            if($this->securityChecker($callback[0]))
            {


                if(is_callable($callback[1]))
                {


                    return  $this->runCallable($callback[1]);
            
                }elseif(is_string($callback[1])){
            
            

                     return   $this->runController($callback[1]);
            

            
                }
            
            
            }
            
        }elseif(strstr($callback[0],"@") && is_callable($callback[1]))
        {





            if(is_callable($callback[1])){

                if($this->runCallable($callback[1]))
                {

                  return  $this->runController($callback[0]);

                }
            }


            
        }elseif(strstr($callback[0],"|") && is_array($callback[1])){

            if($event=  $this->eventParser($callback[0])){

              return  $this->runEvent($event, $callback[1]);

            }



        }elseif(strstr($callback[0],"@") && is_array($callback[1])){



            if($event = $this->runEvent($callback[1][0],$callback[1][1])){

                return $this->runController($callback[0]);

            }

        }
        
    }

    /**
     * Event parçalama işlemi yapar
     * @param $string
     * @return mixed
     */
    private function eventParser($string)
    {

          list(, $event) = explode("|",$string);

          return $event;

    }
    
    /**
     * callback olay�n�n 0. indisi array ise �a�r�l�r
     * @param array $callback
     */
    private function callBackArray(array $callback)
    {

        $callback = $callback[0];

        if(is_string($callback[0]) && is_array($callback[1]))
        {
            
          $event = $this->runEvent($callback[0],$callback[1]);



            
        }
        
    }
    /**
     * Https den girilip girilmedi�ini alg�lar
     * @param unknown $https
     */
    private function securityChecker($https){


        $isSecure = false;
        if($https == "HTTPS" || $https == "https://")
        {
            
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
                $isSecure = true;
            }
            elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
                $isSecure = true;
            }
           
            
        }
       elseif($https == "AJAX"){

           if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
           {


               $isSecure = true;


           }else{

               $isSecure = false;
               
           }
           
           
       }else{
           
           $isSecure = true;
           
       }
        
       return  $isSecure ? true : false;
        
    }

    /**
     *
     *  Callable bir fonksiyonu çağırır
     *
     * @param $callback
     * @return mixed
     */
    private function runCallable($callback)
    {
        
        $parametres = $this->params;
        
        
        $response = Singleton::make('\Myfc\Http\Response');
        
        $parametres[] = $response;
        
        return call_user_func_array($callback, $parametres);
        
    }

    /**
     *
     *  Kontroller çağırır ve girilen parametreleri atar
     *
     * @param $string
     * @return mixed
     */
    private function runController($string)
    {
        
        $parametres = $this->unsetParams;
        
        if(strstr($string, "@"))
        {
            
            list($controller,$method) = explode("@",$string);
            
        }else{
            
            $controller = $string;
            
            $method = null;
            
        }
        
        
        if($method !== null)
        {
            
            $controller = $this->container->makeController($controller);
            
            if(method_exists($controller, $method) && is_callable(array($controller,$method)))
            {
                
                return call_user_func_array(array($controller,$method), $parametres);
                
            }
            
        }else{
            
               return  $controller = $this->container->makeController($controller,$parametres);
             
        }
        
       
        
    }

    /**
     *
     *  Event yürütmekte kullanılır
     *
     * @param string $eventName
     * @param array $parametres
     * @return bool
     */
    private function runEvent($eventName = '', array $parametres)
    {



        if(Events::hasListeners($eventName))
        {

           return Events::fire($eventName, $parametres);


        }else{

            return false;

        }




    }
    
    
}


?>