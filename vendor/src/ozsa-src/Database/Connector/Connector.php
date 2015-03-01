<?php
 namespace Database\Connector;

 class Connector
 {


     public $databaseConfigs;

     public $connectionAdapter;

     protected $creator;


     public function __construct( $databaseConfigs = null)

     {

         if( $databaseConfigs != null )

         {
             $this->databaseConfigs = $databaseConfigs;
         }
         else
         {
             $this->databaseConfigs = require APP_PATH.'Configs/databaseConfigs.php';
         }

         if( $this->databaseConfigs['autoCreateModals'] === true )
         {

             $this->creatorStarter();

         }

     }
     protected function creatorStarter()
     {

       $this->creator = \Desing\Single::make('\Database\Schema\Creator',APP_PATH.'Configs/');


     }
     public function addConnection( Array $array = [] )
     {

         $this->databaseConfigs['Connections'] = $array;

     }

     public function getName()
     {
         return "Connector";
     }
     public function boot()
     {



         $default = $this->databaseConfigs['default'];

         $defaultConnection = $this->databaseConfigs['Connections'][$default];

         $default = $defaultConnection['driver'];

         $adapterName = $default.'Connector';

        $this->connectionAdapter = \Desing\Single::make('Database\Connector\Conntector'.$default,$defaultConnection);


         return $this->connectionAdapter;

     }

     public function __call( $name ,$params)
     {

         $return =  call_user_func_array(array($this->connectionAdapter,$name),$params);
         if(  $return )
         {

              return $return;

         }else{
             throw new \Database\Exceptions\MethodExceptions\undefinedMethodException( sprintf(" %s sınıfı için yaptığınız %s method çağırması başarısız oldu, Dönen yanıt : %s",$this->connectionAdapter->getName(),$name,$this->connectionAdapter->errorInfo()[2]));
         }

     }

 }