<?php





  class Database

  {

      const FETCH_OBJ = 5;

      const FETCH_ASSOC = 2;

      const FETCH_NUM = 3;

      const FETCH_BOTH = 4;

      const EXTENDTABLE = 'table_name';

      protected $selectedTable;

      public $adapter;

      protected $limit;

      protected $set;

      protected $get;

      protected $select = ['*'];

      protected $where;

      protected $like;

      protected $join;

      protected $default;

      protected $with;

      protected $lastQuery;

      protected $pagination;

      protected $lastFetch;

      protected $extentedClass;

      protected static $boot;

      protected $specialWhere = false;

      /**
       * @param string $selectedTable
       *
       *  Başlatıcı sınıf, seçili olan tabloyu ayarlar ve adapter leri kullanıma hazırlar
       */
      public function __construct( $selectedTable = '')
      {

          $this->adapter = \Desing\Single::make( 'Adapter\Adapter','Database' );

          $this->adapter->addAdapter(\Desing\Single::make('\Database\Connector\Connector'));

          $this->adapter->addAdapter(\Desing\Single::make('\Database\Finder\tableFinder',$this->adapter->Connector));

          $this->adapter->alLAdaptersBoot();

          if($selectedTable !== '' )
          {

              $this->selectedTable = $selectedTable;

          }else{

              $this->extentedClass = get_called_class();

              $vars = get_class_vars($this->extentedClass);

              if( isset($vars[static::EXTENDTABLE]))

              {

                  $this->selectedTable = $vars[static::EXTENDTABLE];

              }



          }

          static::$boot = $this;

      }



      public static function boot( $selectedTable = '' )
      {


         return new static( $selectedTable );


      }

      public function table( $tableName = '' )

      {


           if(method_exists($this->adapter->tableFinder,'find') )
           {

               if($this->adapter->tableFinder->find( $tableName )){

                   $this->selectedTable = $tableName;


               }else{

                   throw new Database\Exceptions\QueryExceptions\unsuccessfullFindTableException( sprintf(" %s tablosu veritabanınız da bulunamadı",$tableName));

               }
           }else{

               throw new Database\Exceptions\MethodExceptions\undefinedMethodException( sprintf(" %s sınıfında yapmış olduğunuz %s methodu çağırma işlevi başarısız oldu",$this->adapter->tableFinder->getName(),'find'));

           }


           return $this;
      }

      public function addSet( $name,$value )
      {

          if( !isset($this->set[$this->selectedTable][$name]) )

          {

              $this->set[$this->selectedTable][$name] = $value;

          }

      }

      public function addGet( $name )
      {

          if( !isset( $this->get[$this->selectedTable][ $name ]) )
          {

               $this->get[$this->selectedTable][ $name ];

          }

      }


      public function addSpecialWhere(array $array = array()){

          foreach($array as $key)
          {

              $this->specialWhere[$this->selectedTable][] = $key;

          }

      }

      public function addSpecial($ilk,$orta,$son){

          $this->specialWhere[$this->selectedTable][] = func_get_args();

      }
      /*
        *  [ 'INNER JOIN' => [
        *    'yazarlar','id','id'
        *   ]
        * reset($where);
        * list ($key1, $val1) = each($where);
       */


      public function with( $wid = '')
      {
          $this->with[$this->selectedTable] = $wid;

          $this->que($this->read(false),true);

          return $this;
      }

      public function setArray( Array $array = [] )
      {

          foreach ( $array as $key => $value )
          {

              $this->addSet( $key, $value );

          }

          return $this;

      }

      public function getArray( Array $array = [] )
      {

          foreach ( $array as $key )
          {
              $this->addGet( $key );
          }

          return $this;

      }

      public function select( $select )
      {

          $this->select[$this->selectedTable] = $select;


          return $this;
      }

      public function like(  $array = '' )
      {

          $this->like[$this->selectedTable]=$array;

          $this->que($this->read(false),true);

          return $this;

      }

      public function join( Array $join = [] )
      {

          $this->join[$this->selectedTable] = $join;

          $this->que($this->read(false),true);

          return $this;

      }

      public function where( Array $where = [] )
      {

          $this->where[$this->selectedTable] = $where;

          $this->que($this->read(false),true);

          return $this;

      }



      public  function find(  $int  )

      {

           if(!is_array($int))
           {
               $int = array($int);
           }

            $this->limit[$this->selectedTable] = $int;

            $this->que($this->read(false),true);

            return $this;

      }

      public function wither($with,$where)
      {
          reset($where);

          list($key,$value) = each($where);

          return $this->joiner( ['INNER JOIN' => [
               $with,$key,$key
          ]]);

      }

      public function mixer(array $array,$end)
      {
          $s = "";

              foreach($array as $key => $value)
              {
                  $s .= $key.'='. "'$value'".$end;
              }


          return rtrim($s,$end);


      }

      public function specialer( array $special = array() )
      {
          $s = "";

         foreach($special as $array ){

             $s .= $array[0].$array[1]."'{$array[2]}' AND ";

         }
          return rtrim($s," AND ");
      }

      /**
       * @param $array
       * @return string
       */
      public function wherer($array)
      {
          $s = "";

          foreach ( $array as $whereKey => $whereValue) {
              $s .= $whereKey . '=' . "'$whereValue' AND ";
          };
          if(is_array($this->specialWhere[$this->selectedTable]))
          {
              $s .= $this->specialer($this->specialWhere[$this->selectedTable]);
              $return = $s;
          }else{
              $return = rtrim($s," AND ");
          }

         return $return;


      }

      /**
       * @param array $array
       * @return string
       */
      public function liker(array $array)
      {

          foreach (  $array as $likeKey => $likeValue)
          {
              $like = $likeKey.' LIKE '.$likeValue.' ';
          }
          return $like;

      }

      /**
       * @param $join
       * @return string
       *
       *  [ 'INNER JOIN' => [
       *    'yazarlar','id','id'
       *   ]
       */
      public function joiner($join)
      {
          foreach($join as $joinKey => $joinVal)
          {
              $val = $joinKey.' '.$joinVal[0].' ON '.$joinVal[0].'.'.$joinVal[1].' = '.$this->selectedTable.'.'.$joinVal[2];
          }

          return $val;
      }

      /**
       * @param $limit
       * @return string
       */
      public function limiter($limit)
      {
          $limitbaslangic = $limit[0];

           $return = $limitbaslangic;

          if(isset($limit[1]))
          {
              $limitson = $limit[1];
              $return .= ','.$limitson.' ';
          }

          return $return;



      }

      /**
       * @param array $select
       * @return string
       */
      public function selecter( $select)
      {

          $s = '';
          if (is_array($select)) {
              foreach ($select as $selectKey) {
                  $s .= $selectKey . ',';
              }
              return rtrim($s, ',');
          } else {
              return "*";
          }

      }


      public function create()
      {
          $table = $this->selectedTable;

          $msg = ' INSERT INTO '.$this->selectedTable.' SET '.$this->mixer($this->set[$table],',');

          return $this->que( $msg, false);

      }

      /**
       * @return PDOStatement
       */
      public function update()
      {
          $table = $this->selectedTable;
          $where = $this->where[$table];
          $msg = ' UPDATE '.$table.' SET '.$this->mixer($this->set[$table],', ').' WHERE '.$this->wherer($where);
          return $this->que( $msg, false);
      }

      /**
       * @return PDOStatement
       */
      public function delete()
      {
          $table = $this->selectedTable;
          $where = $this->where[$table];
          $msg = 'DELETE FROM '.$table.' WHERE '.$this->wherer($where);
          return $this->que( $msg, false);
      }

      /**
       * @return $this
       */

      public function returnValues()
      {

        return $this->lastFetch;

      }

      public function read($que = true)
      {
          $table = $this->selectedTable;
          $where = $this->where[$table];
          $like  = $this->like[$table];
          $join  = $this->join[$table];
          $limit = $this->limit[$table];
          $with =  $this->with[$table];

          //where baslangic

          if(is_array($where))
          {
              $where = $this->wherer($where);
          }
          // where son

          // like baslangic
          if(is_array($like))
          {
              $like =   $this->liker($like);
          }
          // like son

          //join baslangic

          if(is_array($join) && !$with && !is_string($with) )
          {
              $join = $this->joiner($join);
          }elseif( $with && is_string($with) ){

              $join = $this->wither($with,$where);

          }

          //join son

          //select baslangic

          $select = $this->selecter($this->get[$table]);

          //select son

          //limit başlangıç

          $limit =  $this->limiter($limit);



          //limit son

          $msg = 'SELECT '.$this->selecter($this->get[$table]).' FROM '.$this->selectedTable.' ';

          if ( isset($join) && is_string($join) )
          {
              $msg .= $join;
          }

          if ( isset ($where) && is_string($where) )
          {
              $msg .= ' WHERE '.$where;
          }

          if( isset($like) && is_string($like) )
          {
              if( isset( $where ) && is_string( $where )) $msg .= ' AND '.$like;
              else $msg .= ' WHERE '.$like;
          }

          if ( isset($limit ) && !is_array($limit) )
          {
              $msg .= ' LIMIT '.$limit;
          }

          return ($que) ? $this->que($msg):$msg;

      }

      public function que($msg ,$fetch = false)
      {


           $this->lastQuery = $msg;



           $query = $this->adapter->Connector->query($msg);

          if($query)
          {
              if( $fetch  )
              {

                  $query = $query->fetch(static::FETCH_OBJ);

                  $this->lastFetch = $query;

              }


          }else{
              throw new Database\Exceptions\QueryExceptions\unsuccessfulQueryException( 'Sorgu başarısız',$msg);
              return false;
          }



          return  $query;




      }

      public function pagination()
      {

        $this->pagination =  Desing\Single::make('\Html\Pagination',$this->adapter->Connector->query($this->lastQuery)->rowCount());

          return $this->pagination->execute(true);

      }


      public function flush()
      {
          $this->set = array();
          $this->get = array();
          $this->mixed = array();
          $this->columns = array();
          $this->tableNames = array();
          $this->where = array();
          $this->join = array();
          $this->limit = array();
          $this->like = array();
          return null;
      }


      public function __call( $name, $params )
      {
          $ac = substr($name,0,3);


          if($ac == 'Get') {
              $this->addGet(str_replace("Get","",$name));
          }
          elseif($ac == 'Set') {
              $this->addSet($params[0],str_replace("Set","",$name));
          }
          else{
              return call_user_func_array(array( $this->adapter->Connector,$name),$params);
          }

          return $this;



      }

      public function __get( $name )
      {

        return (isset($this->lastFetch) && is_object($this->lastFetch)) ? $this->lastFetch->$name:false;
      }

      public function __set( $name, $value )
      {

           $this->set[$this->selectedTable][$name] = $value;

          return  $this;

      }




  }