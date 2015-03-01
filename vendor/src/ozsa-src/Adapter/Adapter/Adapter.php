<?php

  namespace Adapter;

   class Adapter
   {
        public $adapter;
        public $page;
        public function __construct( $page = ' ')
        {
            $this->page = $page;
            return $this;
        }
       public function setPage($page = '')
       {
           $this->page = $page;
           return $this;
       }
       public function alLAdaptersBoot()
       {
           foreach ( $this->adapter[$this->page]  as $key => $values )
           {

                $values['adapter']->boot();

           }

       }
        public function addAdapter( $adapter, $priority = 0)
        {


                  $this->adapter[$this->page][$adapter->getName()] = array(
                      'adapter' => $adapter,
                      'priority' => $priority,
                      'selected' => false,
                  );

                return $this;

        }

       public function setSelect( $name )
       {
           $this->resetAdapterSelection();
           $this->adapter[$this->page][$name]['selected'] = true;
           return $this;
       }
       public function sortAdapter()
       {
           uasort($this->adapter[$this->page],function(array $a,array $b)
           {
               if ($a['selected'] || $b['selected']) {
                   return $a['selected'] ? -1 : 1;
               }

               return $a['priority'] > $b['priority'] ? -1 : 1;
           });
       }
       public function useBestAdapter()
       {
            $this->resetAdapterSelection();
            return $this->sortAdapter();
       }
       public function resetAdapterSelection()
       {
           $this->adapter[$this->page] = array_map( function($pro)
           {
               $pro['selected'] = false;
               return $pro;
           },$this->adapter[$this->$page]);
       }
       public function buildAdapter( $adapter, Callable $call  )
       {

            if(! is_object( $adapter ) )
            {

                 if(isset($this->adapter[$this->page][$adapter]))
                 {
                      $adapter = $this->adapter[$this->page][$adapter]['adapter'];

                 }else{
                     throw new Exception(" $adapter Adında bir adapter bulunamadı ");
                 }

            }

           $call($adapter);

           return $this;
       }
     public function getAdapters()
     {
         return array_values(array_map(function (array $adapter) {
             return $adapter['adapter'];
         }, $this->adapter[$this->page]));
     }
       public function getAdapter($name)
       {
           return $this->adapter[$this->page][$name]['adapter'];
       }
       public function removeAdapter($name)
       {
           if(isset($this->adapter[$this->page][$name])) unset($this->adapter[$this->page][$name]);
       }
       public function removeAdapters()
       {
           $this->adapter[$this->page] = array();
       }

       public function returnAdapterList()
       {
           return $this->adapter[$this->page];
       }

       public function __get($name)
       {

           return $this->adapter[$this->page][$name]['adapter'];
       }



   }