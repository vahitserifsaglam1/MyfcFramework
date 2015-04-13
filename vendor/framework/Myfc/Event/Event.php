<?php
 
 namespace Myfc;
 
 use Myfc\Bootstrap;
 class Event
 {
     
     /**
      * Event listesi
      * @var array
      */
     
     private $listen;
     
     /**
      * wildcard listesi
      * @var unknown
      */
     
     private $wildcards = array();
     
     /**
      * S�ralanan eventler
      * @var array
      */
     
     private $sorted;
     
     /**
      * 
      * @var array
      */
     
     private $firing;
     
     
     /**
      * 
      * @var Container
      */
     private $container;
     
     
     public function __construct(Bootstrap $container = null)
     {
         
        $this->container = $container;
         
     }
     
     /**
      * 
      *  Event dinlemek i�in yeni bir dinleyici olu�turur
      * 
      * @param string $event
      * @param mixed $listener
      * @param number $properity
      */
     
     public function listen($event = '', $listener, $properity = 0)
     {
         
         if(strstr($event, "*"))
         {
             
             $this->setupWilcard($event, $listener);
             
         }else{
             
             $this->listen[$event][$properity][] = $this->makeListener($listener); 
             
             unset($this->sorted[$event]);
             
         }
        
    

     }
     
     /**
      * 
      *  Wildcardlar i�in kurulum yapar
      * 
      * @param string $event
      * @param mixed $listener
      */
     
     private function setupWilcard($event, $listener){
         
         $this->wildcards[$event][] = $this->makeListener($listener);
         
     }
     
     /**
      * Listener olu�turur
      * @param unknown $listener
      * @return unknown
      */
     
     public function makeListener($listener)
     {
         return is_string($listener) ? $this->createClassListener($listener) : $listener;
     }
     
     
     
     private function createClassListener($listener = '')
     {
         
         $container = $this->container;
         
         return function() use ($listener, $container)
         {
             return call_user_func_array(
                 $this->createClassCallable($listener, $container), func_get_args()
             );
         };
         
     }
     
     /**
      * �a�r�labilir s�n�f fonksiyono olu�turur
      * @param unknown $listener
      * @param Container $container
      * @return multitype:NULL multitype:\Myfc\Ambigous
      */
     
     protected function createClassCallable($listener,Container $container)
     {
         list($class, $method) = $this->parseClassCallable($listener);
     

             return array($container->make($class), $method);
         
     }
     
     /**
      * �a�r�labilir fonksiyonu s�n�f method olarak par�alar
      * @param unknown $listener
      * @return multitype:Ambigous <string, unknown> Ambigous <>
      */
     
     protected function parseClassCallable($listener)
     {
         $segments = explode('@', $listener);
     
         return [$segments[0], count($segments) == 2 ? $segments[1] : 'handle'];
     }
     
     /**
      * 
      *  Listeners varm� yokmu diye kontrol eder , true yada false d�ner
      * 
      * @param boolean $eventName
      */
     
 public function getListeners($eventName)
	{
		$wildcards = $this->getWildcardListeners($eventName);

		if ( ! isset($this->sorted[$eventName]))
		{
			$this->sortListeners($eventName);
		}

		return array_merge($this->sorted[$eventName], $wildcards);
	}
	
	/**
	 * Wildcart listenerslar� al�r
	 * @param unknown $eventName
	 * @return multitype:
	 */
	protected function getWildcardListeners($eventName)
	{
	    $wildcards = array();
	    
	    foreach ($this->wildcards as $key => $listeners)
	    {
	        if (str_is($key, $eventName)) $wildcards = array_merge($wildcards, $listeners);
	    }
	
	    return $wildcards;
	}
	
	/**
	 * Sort the listeners for a given event by priority.
	 *
	 * @param  string  $eventName
	 * @return array
	 */
	protected function sortListeners($eventName)
	{
	    $this->sorted[$eventName] = array();
	
	    // If listeners exist for the given event, we will sort them by the priority
	    // so that we can call them in the correct order. We will cache off these
	    // sorted event listeners so we do not have to re-sort on every events.
	    if (isset($this->listen[$eventName]))
	    {
	        krsort($this->listen[$eventName]);
	
	        $this->sorted[$eventName] = call_user_func_array(
	            'array_merge', $this->listen[$eventName]
	        );
	    }
	}
	
	/**
	 * Listener olup olmad���n� kontrol eder
	 * @param string $eventName
	 */
     public function hasListeners($eventName)
     {
         return isset($this->listen[$eventName]);
     }
     
     
     public function fire($event = '', $payload = array(), $halt = false)
     {
         
         $cevap = array();
         
         $responses = array();
         
         if(!is_array($payload)) $payload = array($payload);
         
         $this->firing[] = $event;
         
         foreach($this->getListeners($event) as $listener){
             
             $cevap = call_user_func_array($listener, $payload);
             
             if ( ! is_null($cevap) && $cevap &&  $halt)
             {
                 array_pop($this->firing);
             
                 return $cevap;
             }
             
             if ($cevap === false) break;
             
             $responses[] = $cevap;
         }
         
         array_pop($this->firing);
         
         return $halt ? null : $responses;
         
     }
     
     /**
      * Listeneri siler
      * @param unknown $event
      */
     public function forget($event)
     {
         unset($this->listen[$event], $this->sorted[$event]);
     }
     
     /**
      * En son �a�r�lan itemi getirir
      * @return mixed
      */
     public function firing()
     {
         return end($this->firing);
     }
      
      
     
     
 }