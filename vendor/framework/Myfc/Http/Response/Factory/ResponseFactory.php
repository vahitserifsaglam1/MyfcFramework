<?php

   namespace Myfc\Http\Response\Factory;

   use Myfc\Redirect;



   class ResponseFactory{

        protected $response;

        protected $view;

        protected $content;

        protected $status;



       public function __construct( $response, $view, Redirect $reditect )
       {

           $this->response = $response;

           $this->view = $view;

           $this->reditect = $reditect;

       }

       public  function make($content = '', $status = 200, array $headers = array())
       {
           $this->content = $content;
           $this->status = $status;
           return $this->response->create($content, $status, $headers);


       }

       public function view($view, $data = array(), $status = 200, array $headers = array())
       {
           return static::make($this->view->load($view,false,$data), $status, $headers);
       }


       public function __call($name,$params)
       {
           if(method_exists($this->response,$name ) )
           {
               return call_user_func_array(array($this->response,$name),$params);
           }
           else{

               $return =  call_user_func_array(array($this->reditect,$name),$params);
               echo "<title>".$this->content."</title>";
               new \MyException($this->content,$this->status,URL,0);
               return $return;

           }


       }


   }