<?php 
 
 
   return [
       
       
       'Connection' => 'mysql',
       
       'autoQuery'  => true,
        
       'fetch'      => 'OBJ', // OBJ,ASSOC,NUM,BOTH

       'Connections' => [

           'mysql' => [

               'host'     =>  'localhost',
               'dbname'   =>  'kutup',
               'username' =>  'root',
               'password' =>  '',
               'charset'  =>  'utf-8',
               'driver'   =>  'pdo'

           ],

           'mangodb' => [

               'host' => "127.0.0.1",
               'port' => 27017

           ]

       ]
       
        
       
   ];

?>