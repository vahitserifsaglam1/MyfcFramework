<?php
namespace Myfc;
 
 use Myfc\Bootstrap;
 use Myfc\Facade\Event as Events;
/**
 *
 * @author vahitşerif
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
     * Link kontrolu ba�lang��
     * @param unknown $action
     * @return unknown
     */
    private function actionParsing($action)
    {
       

              $url = explode("/",rtrim($this->url,"/"));

              $action = rtrim($action,"/");

              $explode = explode("/",$action);

              // url ve parametrelerin karşılaştırılıp dizi oluşturulması
              $array = $this->urlParsingArray($url, $explode);

              // url ve paremetrelerin karşılaştırılıp nullların çıkarılması

              $unsetArray = $this->urlParsingWithoutNulls($array);

                  
                         preg_match_all("#\{(.*?)\}#", $action,$finds);
                               
                        $params = $this->paramsCreate($url, $array);

                 
                         $find = $finds[1];


                       $unsetParams = $this->paramsCreateWithoutNulls($url,$unsetArray);

                 
                             $this->unsetParams = $this->paramsWhereCheck($unsetParams);
                
                 
                             $this->params = $this->paramsWhereCheck($params);
                            
                 
                             return (is_array($this->unsetParams) && is_array($this->params)) ? true:false;
                 
             }


    /**
     *
     *  url ve paremetrelerin karşılaştırılıp dizi oluşturulması
     *
     * @param array $url
     * @param array $explode
     * @return array
     */

    private function urlParsingArray( array $url , array $explode)
    {



        if(count($url) && count($explode)) {
            $array = array_map(function ($a) {

                if (strstr($a, "{") && strstr($a, "}")) {


                    return $a;

                }

            }, $explode);

            return $array;

        }

    }

    /**
     *
     *  Parametrelerden null olanların çıkartılması
     *
     * @param array $array
     * @return array
     */

    private function urlParsingWithoutNulls(array $array)
    {


        $unsetArray = array_map(function($a){

            if(!strstr($a,"!"))
            {

                return $a;

            }

        }
            , $array);

        return $unsetArray;

    }

    /**
     *  Parametrelerin oluşturulması
     * @param array $url
     * @param array $array
     * @return array
     */
    private function paramsCreate(array $url, array $array)
    {

        $params = array();



        for($i=0;$i<count($array);$i++)
        {

            if(isset($url[$i]) && $array[$i] !== null)
            {


                $params[$array[$i]] = $url[$i];

            }

        }

        return $array;

    }

    /**
     *
     *  Parametreler oluşturulurken null olanların çıkartılması
     *
     * @param array $url
     * @param array $unsetArray
     * @return array
     */

    private function paramsCreateWithoutNulls(array $url, array $unsetArray)
    {

        $unsetParams = array();

        for($a=0;$a<count($url);$a++)
        {


            if(isset($url[$a]) && $unsetArray[$a] !== null )
            {

                $unsetParams[$unsetArray[$a]] = $url[$a];

            }

        }

         return $unsetParams;


    }

    /**
     * parametrelerde where tanımına uyanların karşılaştırılması
     * @param array $params
     * @return array
     */
    
    private function paramsWhereCheck(array $params = array())
    {
        $where = $this->collection['WHERE'][0];
        
        $parametres = array();
        
        foreach ($params as $param => $value)
        {
            
  
            preg_match_all("#\{(.*?)\}#", $param,$finds);
           
            if(isset($finds[1][0]))
            {

                $param = $finds[1][0];

                if(isset($where[$param]))
                {


                    preg_match($where[$param], $value, $finded);
                    $parametres[] = $finded[0];


                }else{

                    $parametres[] = $value;

                }

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
            
                    $this->runCallable($callback[1]);
            
                }else{
            
            
                    if(is_string($callback[1]))
                    {
            
                        $this->runController($callback[1]);
            
                    }
            
                }
            
            
            }
            
        }elseif(strstr($callback[0],"@"))
        {
            
            if($this->runCallable($callback[1]))
            {
                
                $this->runController($callback[0]);
                
            }
            
        }    
        
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
            
            $this->runEvent($callback[0],$callback[1]);
            
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
       elseif($https =="AJAX"){
           
           if(filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') === 'xmlhttprequest')
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