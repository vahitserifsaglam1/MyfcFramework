<?php

  namespace Database\Finder;


  class databaseFinder

  {

      public $connector;

      public $databases;

      public $finder;

      public function  __construct( $connector)

      {

          $this->connector = $connector;

          $this->finder = \Desing\Single::make('\Database\Finder\tableFinder',$this->connector);

          $this->connector->boot();


      }


      public static function boot( $connector )

      {

          return new static( $connector );

      }

      public function returnDatabases()
      {

          $query = $this->connector->query( "SHOW DATABASES ");

          while( $fetch = $query->fetch(\Database::FETCH_NUM))
          {

              $this->databases[$fetch[0]] = $fetch[0];

          }

      }

      public function creator($oldname, $newname, $mixed )
      {
         $msg = "RENAME TABLE ";
          foreach( $mixed as $key => $value)
          {

              $msg .= "$oldname.$key TO $newname.$key,";

          }

           $msg = rtrim($msg,",");

           $msg .= ";";

          return $msg;

      }

      public function changeName( $oldname, $newname )
      {
           if( !$this->databases )
           {

               $this->returnDatabases();

           }

          if( $this->databases[$oldname])
          {
              $query = "CREATE DATABASE $newname;";

              $create = $this->creator($oldname,$newname, $this->finder->returnTables() );

              $query .= $create;

              $query .= "DROP DATABASE $oldname;";

              $query = $this->connector->query($query);

              return $query;
          }else{

              throw new \Database\Exceptions\databaseExceptions\notfoundDatabaseException( sprint_f(" %s tablasu bulunamadığı için %s ile değiştirilemedi ",$oldname,$newname));

          }

      }


  }