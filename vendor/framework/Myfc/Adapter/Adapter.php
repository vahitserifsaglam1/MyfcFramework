<?php

  namespace Myfc;

  /**
   * Class Adapter
   * @package Adapter
   *
   */

   class Adapter
   {
       /**
        * @var
        */
        public $adapter;

       /**
        * @var string
        */

        public $page;

       /**
        * @param string $page
        */
        public function __construct( $page = ' ')
        {
            $this->page = $page;
        }

       /**
        * @param string $page
        * @return $this
        */

       public function setPage($page = '')
       {
           $this->page = $page;
           return $this;
       }

       /**
        *  call the boot method all adapters
        */

       public function alLAdaptersBoot()
       {
           foreach ( $this->adapter[$this->page]  as $key => $values )
           {

                $values['adapter']->boot();

           }

       }

       /**
        * @param $adapter
        * @param int $priority
        * @return $this
        */

        public function addAdapter( $adapter, $priority = 0)
        {


                  $this->adapter[$this->page][$adapter->getName()] = array(
                      'adapter' => $adapter,
                      'priority' => $priority,
                      'selected' => false,
                  );

                return $this;

        }

       /**
        * @param $name
        * @return $this
        */

       public function setSelect( $name )
       {
           $this->resetAdapterSelection();
           $this->adapter[$this->page][$name]['selected'] = true;
           return $this;
       }

 
       /**
        *  Adapterleri s�ralama yapar
        * @return number
        */
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

       /**
        *  use 1. adapter
        */

       public function useBestAdapter()
       {
            $this->resetAdapterSelection();
            return $this->sortAdapter();
       }

       /**
        *
        *  Reset the selected adapter
        *
        */

       public function resetAdapterSelection()
       {
           $this->adapter[$this->page] = array_map( function($pro)
           {
               $pro['selected'] = false;
               return $pro;
           },$this->adapter[$this->$page]);
       }

       /**
        * @param $adapter
        * @param callable $call
        * @return $this
        * @throws Exception
        */

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

       /**
        * @return array
        */

     public function getAdapters()
     {
         return array_values(array_map(function (array $adapter) {
             return $adapter['adapter'];
         }, $this->adapter[$this->page]));
     }

       /**
        * @param $name
        * @return mixed
        */

       public function getAdapter($name)
       {
           return $this->adapter[$this->page][$name]['adapter'];
       }

       /**
        * @param $name
        */

       public function removeAdapter($name)
       {
           if(isset($this->adapter[$this->page][$name])) unset($this->adapter[$this->page][$name]);
       }

       /**
        *
        */

       public function removeAdapters()
       {
           $this->adapter[$this->page] = array();
       }

       /**
        * @return mixed
        */

       public function returnAdapterList()
       {
           return $this->adapter[$this->page];
       }

       /**
        * @param $name
        * @return mixed
        */

       public function __get($name)
       {

           return $this->adapter[$this->page][$name]['adapter'];
       }



   }