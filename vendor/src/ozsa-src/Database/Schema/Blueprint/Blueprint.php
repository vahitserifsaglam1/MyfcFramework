<?php

  namespace Database\Schema;


  class Blueprint
  {

      public static $booted = false;

      public  $Text;

      public  $Integer;

      public  $Varchar;

      public  $Timestamp;

      public  $Increments;

      public  $table;

      public $database;

      public $default;

      public $primary;

      public $date;

      public function __construct( $tableName = ' ')
      {

          $this->table = $tableName;

          $this->database  = \Desing\Single::make('\Database',$this->table);

          $this->adapter = \Desing\Single::make('\Adapter\Adapter','Blueprint');

          $this->adapter->addAdapter(\Desing\Single::make('\Database\Schema\Blueprint\Creator\TableCreator',$this));

      }

      public static function boot( $tableName = '')
      {


          return new static( $tableName );

          static::$booted = true;


      }

      public function primary( $id )
      {

          $this->primary[$this->table] = $id;
      }

      public function timestamp( $stn )
      {

          $this->Timestamp[$this->table][] = $stn;

      }
      public function date( $stn )

      {

          $this->date[$this->table][] = $stn;

      }

      public function increment($stn = '')
      {

          $this->Increments[$this->table] = $stn;


      }
      public function integer( $stn, $val = 255 )
      {

          $this->Integer[$this->table][ $stn ] = $val;

      }

      public function text( $text )
      {

          $this->Text[$this->table][] = $text;

      }

      public function varchar( $stn, $val = 255)
      {

          $this->Varchar[ $this->table ][$stn] = $val;
      }
      public function create()
      {

          $this->adapter->TableCreator->create();

      }


  }
