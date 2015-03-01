<?php


  namespace Database\Schema\Blueprint\Creator;

  class TableCreator
  {

      public $that;

      public $table;

      public function __construct( $blueprint )
      {

          $this->that = $blueprint;

          $this->table = $this->that->table;

      }
      public function getName()
      {

          return "TableCreator";

      }

      public function createVarchar()
      {

          if( isset( $this->that->Varchar[$this->table] ) && is_array( $this->that->Varchar[$this->table] ) )
          {

              $varchar = $this->that->Varchar[$this->table];

              $msg = '';

              foreach ( $varchar as $key => $value )

              {

                $msg .= "$key VARCHAR($value),";

              }
              return $msg;

          }

      }

      public function createText()
      {

           @$Text = $this->that->Text[$this->table];

          if( isset($Text) && is_array($Text) ) {


              $msg = '';

              foreach ( $Text as $key )
              {

                 $msg .= "$key text,";

              }

              return $msg;

      }

      }

      public function createPrimary()
      {

          $primary = $this->that->primary[$this->table];



          if ( isset($primary) )
          {

              $msg = "PRIMARY KEY ($primary),";
              return $msg;
          }

      }

      public function createTimestamp()
      {

            $time = $this->that->Timestamp[$this->table];

          if( isset ( $time ) && is_array ( $time ) )
          {
              $msg = '';
              foreach ( $time as $key )
              {

                  $msg .= "$key CURRENT_TIMESTAMP NOT NULL,";

              }

              return $msg;

          }

      }

      public function createDate()
      {

          $date = $this->that->date[$this->table];

          if( isset( $date )  && is_array ( $date ) )
          {

              $msg = '';

              foreach( $date as $key )
              {

                  $msg .= "$key DATE,";

              }

              return $msg;

          }

      }
      public function CreateIncrement()
      {

          $increment = $this->that->Increments[$this->table];

          if( isset($increment) && $increment != '' && $increment )
          {
              $msg = "$increment INT NOT NULL AUTO_INCREMENT,";

              return $msg;

          }




      }

      public function createInteger()
      {
          $Integer = $this->that->Integer[$this->table];
          if( isset( $Integer ) && is_array( $Integer ) )
          {

             $msg  = '';

              foreach ( $Integer as $key => $value )
              {

                  $msg .= "$key INTEGER($value),";

              }

              return $msg;

          }

      }

      public function boot()
      {



      }

      public function create()
      {

          $msg = "CREATE TABLE $this->table (
           ";

          $msg .= $this->CreateIncrement();

          $msg .= $this->createText();

          $msg .= $this->createVarchar();

          $msg .= $this->createInteger();

          $msg .= $this->createDate();

          $msg .= $this->createTimestamp();

          $msg .= $this->createPrimary();

          $msg = rtrim($msg,',');

          $msg .= ");";

          return  $this->that->database->query($msg);



      }


  }