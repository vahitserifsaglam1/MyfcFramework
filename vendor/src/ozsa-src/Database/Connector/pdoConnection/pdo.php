<?php
  namespace Database\Connector;

  class Conntectorpdo extends  \PDO{


         public function __construct( Array $params = array() )
         {


             extract($params);


             try{

                  $connection = parent::__construct("$pdoType:host=$host;dbname=$dbname",$username,$password);

                  return $connection;

             }catch(PDOException $e)
             {

                 return $e->getMessage();
             }


         }

      public function getName()
      {
          return __CLASS__;
      }




  }