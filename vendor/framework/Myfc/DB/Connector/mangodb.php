<?php

 namespace Myfc\DB\Connector;

  use MongoClient;

 class mangodb{

     private $mangodb;

     public function __construct($mangoDB, $Configs = [])
     {

         if($this->checkDriver())
         {

             try{
                 $host = $mangoDB['host'];
                 $port = $mangoDB['port'];

                 $string = "mongodb://$host:$port";

                 if(isset($mangoDB['username']) && isset($mangoDB['password']))
                 {

                     $username = $mangoDB['username'];
                     $password = $mangoDB['password'];

                     $string = "mongodb://$username:$password@$host:$port";

                 }

                 $this->mangodb = new MongoClient();
                 $this->mangodb->selectDB($mangoDB['database']);

             }catch(MongoConnectionException $e)
             {

                 echo $e->getMessage();

             }


         }

     }

     private function checkDriver()
     {

         if(extension_loaded('mangodb'))
         {

             return true;

         }

     }

     private function checkInstall()
     {

         return ($this->mangodb instanceof  MongoClient) ? true:false;

     }


     public function __call($method, $parametres)
     {

         if($this->checkInstall())
         {

             return call_user_func_array([$this->mangodb, $method],$parametres);

         }


     }

 }

