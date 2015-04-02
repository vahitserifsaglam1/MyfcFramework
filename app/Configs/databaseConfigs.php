<?php 
 
 
   return [
       
       
       'Connection' => 'mysql',
       
       'autoQuery'  => true,
        
       'fetch'      => 'OBJ', // OBJ,ASSOC,NUM,BOTH
       
       
       'mysql' => [
        
            'host'     =>  'localhost',
            'dbname'   =>  'kutup',
            'username' =>  'root',
            'password' =>  '',
            'charset'  =>  'utf-8',
            'driver'   =>  'pdo'
            
       ],
       
       'predis' => [
       
           'cluster' => false,
       
           'default' => [
       
               'scheme'   => 'tcp',
               'host'     => '127.0.0.1',
               'port'     =>  6379,
               'database' =>  0,
       
           ]
       
       ]
        
       
   ];

?>