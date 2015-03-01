<?php

   namespace Database\Finder;


   class tableFinder
   {
       public $connector;

       public $tableNames;

       public $columns;

       public $mixed;

       protected $jsonPath =  'Configs/orm.json';

       public function __construct(  $connector )
       {

           $this->connector = $connector;

       }

       public function createJson()
       {
           $path = APP_PATH.$this->jsonPath;

           $json = json_encode(['tables' => $this->mixed]);



           if(!file_exists($path)) touch($path);
           file_put_contents($path,$json);
       }

       public function checkJson( $return_file = false)
       {

           $path = APP_PATH.$this->jsonPath;



           if( file_exists( $path ) )
           {

               if( !$return_file )
               {
                   return true;
               }else{

                   return file_get_contents($path);
               }

           }

       }

       public function getName()
       {

           return "tableFinder";

       }

       public function findByJson( $tablename )
       {
         $content = json_decode( $this->checkJson(true),false);


           if( isset( $content->tables->$tablename ) )
           {

               return true;

           }
       }

       public function boot(  )
       {

           if(!$this->checkJson())

           {

               $this->tables = $this->returnTables();

           }

       }

       public function recheck()
       {

           $path = APP_PATH.$this->jsonPath;

           unlink($path);

           $this->returnTables();

       }

       public function find( $tableName )
       {

           if( $this->checkJson() )
           {

               $return = $this->findByJson( $tableName );

           }else{

               if( isset( $this->tableNames[$tableName] ) )
               {

                   $return =  $this->tableNames[$tableName];

               }
               else{

                   $return =  false;

                   throw new \Database\Exceptions\TableExceptions\undefinedTableException( sprintf( "%s Tablosu Veritabanında bulunamadı ",$tableName) );

               }

           }

         return $return;


       }

       public function findColumns ( $table = '' )
       {
          if( isset ( $this->mixed [ $table ] ) )
          {

              return $this->mixed [ $table ];
          }
       }

       public function returnTables()
       {


           $tableQuery = $this->connector->query("SHOW TABLES");

           $tableName = '';
           while($tableFetch = $tableQuery->fetch(\Database::FETCH_NUM))
           {
               $tableName[$tableFetch[0]] = array();
               $this->tableNames[] = $tableFetch[0];


               $qur = $this->connector->query("describe $tableFetch[0]");
               while($columns = $qur->fetch(\Database::FETCH_NUM))
               {

                   $tableName[$tableFetch[0]][]= $columns;
                   $this->columns[]= $columns;
               }
           }
           $this->mixed = $tableName;

           $this->createJson();
           return $this->mixed;


       }


   }