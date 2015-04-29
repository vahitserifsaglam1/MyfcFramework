<?php
namespace Myfc;

 use Exception;
 use PDOStatement;
 use Myfc\Html\Pagination;
 use Myfc\File\Excel;
 use stdClass;
 use Myfc\Helpers\StringBuilder;

 /**
  *
  * @author vahit�erif
  *        
  */

 class DB
 {
 
     use StringBuilder;
     
     private $selectedTable;
 
     private $where = [];
 
     private $join;
 
     private $set;
 
     private $get;
 
     private $limit;
 
     private $with;
 
     private $like;
 
     private $specialLike;
 
     private $connector;
 
     private $or_where;

     private $order;
     
     private $group;
 
     const EXTENDTABLE =  'table';
 
     const CONFIG_PATH = 'app/Configs/databaseConfigs.php';
 
     const FETCH_OBJ = 5;
 
     const FETCH_ASSOC = 2;
 
     const FETCH_NUM = 3;
 
     const FETCH_BOTH = 4;
 
     private $configs = [];
 
     private $lastQuery;
 
     private $lastSucessQuery;
 
     private $lastQueryString;
 
     private  $fetchName;
 
     private $lastFetch;
 
     private $lastError;
 
     private $lastErrorString;

     private $driverList = [

         'pdo' => 'Myfc\DB\Connector\pdo',
         'mysql' => 'Myfc\DB\Connector\mysql',
         'sqlite' => 'Myfc\DB\Connector\sqlite',
         'mangodb' => 'Myfc\DB\Connector\mangodb'

     ];
 
     /**
      * Sınıfın tutulacağı static değişken
      * @var unknown
      */
 
     private static $instance;
     /**
      *
      *   Başlatıcı Fonksiyon
      *
      *   Seçilen tabloyu parametre olarak $table ile iletebilir, veya herhangi bir sınıftan çekebilirisniz
      *
      */
     public function __construct( $table = '' )
     {
 
         
         if($table !== '')
         {
              
             $this->selectedTable = $table;
              
         }else{
              
              
             $table = get_called_class();
              
             $vars = get_class_vars($table);
              
             if( isset($vars[static::EXTENDTABLE]))
              
             {
                  
                 $this->selectedTable = $vars[static::EXTENDTABLE];
                  
             }else{
                  
                 $this->selectedTable = $table;
                  
             }
              
          
         }
          
          
         if(file_exists(static::CONFIG_PATH))
         {
              
             $this->configs = Config::get('databaseConfigs');
              
         }else{
              
             throw new Exception('veritaban� ayar dosyan�z silinmi�');
              
         }
          
         $this->connect();
 
 
     }
    
     /**
      * Sınıftaki değerleri kullanıma hazırlar, notice hatalarını önler
      */
     private function readyForUse(){
         
         $this->where[$this->selectedTable] = [];
         $this->like[$this->selectedTable] =  [];
         $this->limit[$this->selectedTable] = [];
         $this->order[$this->selectedTable] = [];
         $this->or_where[$this->selectedTable] = [];
     }


     /**
      * Static olarak sınfı başalatır
      * @param string $table
      * @return \Myfc\DB
      */
 
     public static function boot( $table = '')
     {
 
         return new static($table);
 
     }
 
     /**
      * Se�ilen Tabloyu De�i�tirir
      * @param string $table
      * @return \Myfc\DB
      */
 
     public function setTable($table = '')
     {
 
         $this->selectedTable = $table;
 
         return $this;
 
     }
 
     /**
      * Veri tabanı bağlantısı yapar
      *
      *  Hiçbir veri döndürmez
      */
     private function connect()
     {
 
         $configs = $this->configs;
 
         $this->fetchName = $configs['fetch'];
 
         $connect = $configs['Connection'];
 
 
         if(isset($configs['Connections'][$connect]))
         {
 
             $selected = $configs['Connections'][$connect];
 
             $driver = $selected['driver'];

             if(isset($this->driverList[$driver]))
             {

                 $connectorName = $this->driverList[$driver];

             }
 
 
             $this->connector = new $connectorName($selected , $connect);
 
         }else{
 
 
             throw new Exception('yanlış sürücü seçilmiş'.static::CONFIG_PATH.' dosyasında bulunamadı');
 
         }
 
          
           $this->readyForUse();
 
 
     }

     /**
      * @param string $name
      * @param callable $callable
      * @param bool $autocheck
      * @return $this
      *
      *  Sınıfa yeni bir connector ekler
      *
      */
     public function extension($name = '', callable $callable = null, $autocheck = true)
     {

         if(!isset($this->driverList[$name]))
         {

             $response = $callable();
             if(is_object($response))
             {

                 $this->driverList[$name] = get_class($response);

             }elseif(is_string($response)){

                 $this->driverList[$name] = $response;

             }

             if($autocheck === true){

                 $this->driver($name);

             }

         }

         return $this;

     }

     /**
      *
      *  Sınıfın kullacağını driver ı seçer
      *
      * @param string $name
      * @return $this|bool
      */
     public function driver($name = ''){


         if(isset($this->driverList[$name])) {

             $this->configs['Connection'] = $name;

             return $this;

         }
         else{
             return false;
         }

     }
 
    /**
     * 
     *  Örnek kullanımlar 
     * 
     *  ->where(array($baslangic, $orta, $son))
     *  ->where($baslangic,$orta))
     *  ->where($baslangic, $orta,$son))
     *  ->where(array($baslangic => $son)) // $orta = "=" ->default
     * 
     * @param mixed $whereBaslangic
     * @param mixed $whereOrta
     * @param mixed $whereSon
     * @param mixed $orwherekey 
     * @return \Myfc\DB
     */
 
     public function where($whereBaslangic = [], $whereOrta = null, $whereSon = null, $orwhereKey = null )
     {
 
        $where =  $this->whereParser($whereBaslangic,$whereOrta,$whereSon, $orwhereKey);
          
         return $this;
 
     }
     
     /**
      * 
      * @param type $whereBaslangic
      * @param type $whereOrta
      * @param type $whereSon
      * @param type $orwhereKey
      */
     private function whereParser($whereBaslangic = [], $whereOrta = null, $whereSon = null, $orwhereKey = null)
     {
       
         if($whereBaslangic && $whereOrta && !$whereSon){
             
             $this->where[$this->selectedTable][] = [$whereBaslangic => $whereOrta];
             
         }
         elseif($whereBaslangic && $whereOrta && $whereSon && !$orwhereKey){
             
             $this->where[$this->selectedTable][] = [$whereBaslangic,$whereOrta,$whereSon];
             
         }elseif($whereBaslangic && $whereOrta && $whereSon && $orwhereKey){
            
             $this->or_where[$this->selectedTable][] = [$whereOrta,$whereSon,$orwhereKey];
             
         }
         elseif(is_array($whereBaslangic)){
             
             if(isset($whereBaslangic[0])){
                 
                 $test = count($whereBaslangic[0]);
                 
                 if($test == 2){
                     
                 $this->where[$this->selectedTable] = array_merge($this->where[$this->selectedTable], $whereBaslangic);
                     
                 }elseif($test === 3){
                     
                     $this->where[$this->selectedTable] = array_merge($this->specialWhere[$this->selectedTable],$whereBaslangic);
                     
                 }elseif($test === 4){
                     
                     $this->or_where[] = [$whereBaslangic[1], $whereBaslangic[2],$whereBaslangic[3]];
                     
                 }
                 
             }
             
         }
         
         
     }
     
     /**
      * 
      * @param $group
      * @return $this;
      * 
      */
     
     private function group($group = null){
         
         if(!is_null($group)){
             
             $this->group[$this->selectedTable] = $group;
             
         }
         
     }

     /**
      * Sorguya order tanımı ekler
      * @param $id
      * @param string $type
      * @return $this
      */
     public function order($id, $type = 'DESC'){

         $this->order[$this->selectedTable][] = [$id,$type];
         return $this;

     }
     /**
      * ��eri �ekilecek di�er tabloyu ekler
      * @param string $wid
      * @return \Myfc\DB
      */
 
     public function with( $wid = '')
     {
         $this->with[$this->selectedTable] = $wid;
 
         $this->autoQuery();
 
         return $this;
     }
 
     /**
      * Sorguda kullan�lacak parametreleri ekler
      *
      *  �rnek Kullan�m : $db->set( array('id' => 1 ) )
      *
      * @param array $array
      * @return \Myfc\DB
      */
 
     public function set( array $array = [] )
     {
 
         foreach ( $array as $key => $value )
         {
 
             $this->addSet( $key, $value );
 
         }
 
         return $this;
 
     }
 
     /**
      * Sorguda �ekilecek sutunlar� ekler
      *
      *  �rnek Kullan�m : $db->get(array('column1','column2))
      *
      * @param array $array
      * @return \Myfc\DB
      */
 
     public function get( array $array = [] )
     {
 
         foreach ( $array as $key )
         {
             $this->addGet( $key );
         }
 
         return $this;
 
     }
 
     /**
      * Sorguya Join ekler
      *
      *   �rnek Kullan�m: $db->join( array ( 'INNER JOIN' => array('kitap','fakulte','id)
      *
      * @param array $join
      * @return \Myfc\DB
      */
 
     public function join( array $join = [] )
     {
 
         $this->join[$this->selectedTable][] = $join;
 
         $this->autoQuery();
 
         return $this;
 
     }
 
     /**
      *
      *  Sorguya Like ekler
      *
      *   �rnek kullan�m :$db->like(array('id' => 5))
      *
      * @param string $array
      * @return \Myfc\DB
      */
 
     public function like(  $array = ''  )
     {
 
 
          
         $this->like[$this->selectedTable][]=$array;
 
         $this->autoQuery();
 
         return $this;
 
     }
 
 
 
     /**
      * Limit atamas� yapar
      * @param unknown $limit
      * @return \Myfc\DB
      */
     public function limit($limit = [])
     {
 
         if(is_array($limit))
         {
 
             $this->limit[$this->selectedTable] = $limit;
 
         }else{
 
             $this->limit[$this->selectedTable] = func_get_args();
 
         }
 
         $this->autoQuery();
 
         return $this;
     }
 
     /**
      * Sorguya tekil special like ekler
      *
      *  �rnek Kullan�m : $db->specialLike('%',$id,'')
      *
      * @param unknown $bas
      * @param unknown $orta
      * @param unknown $son
      * @return \Myfc\DB
      */
 
     public function specialLike($columns = 'column_name',$bas,$orta,$son)
     {
 
         $this->specialLike[$this->selectedTable][] = func_get_args();
 
 
         $this->autoQuery();
 
         return $this;
 
     }
 
 
     public function addSet( $name,$value )
     {
 
         if( !isset($this->set[$this->selectedTable][$name]) )
 
         {
 
             $this->set[$this->selectedTable][$name] = $value;
 
         }
 
         return $this;
 
     }
     /** Veri taban�ndan veri �ekme i�lemi i�in �ekilecek verilerin se�ilmesi  */
     public function addGet( $name )
     {
 
 
         if( !isset( $this->get[$this->selectedTable][ $name ]) )
         {
 
             $this->get[$this->selectedTable][] = $name;
 
         }
 
         return $this;
 
     }
 
     private function autoQuery()
     {
 
 
 
         $configs = $this->configs;
 
 
         if($configs['autoQuery'] === true)
         {
 
             
             (!isset($this->set[$this->selectedTable])) ? $this->read(false):null;
              
 
         }
 
 
     }
 
     private function wither($with,$where)
     {
         
         
         reset($where);
 
         list($key,$value) = each($where);
 
         return $this->joiner( ['INNER JOIN' => [
             $with,$key,$key
         ]]);
 
     }
    
 
     private function specialer( array $special = [] )
     {
         
      
         if(count($special) > 0){
             
                      $s = " AND ";
         foreach($special as $array ){
 
             $s .= $array[0].' '.$array[1].' '."'{$array[2]}' AND ";
 
         }
         return rtrim($s," AND ");
             
         }
         

     }
     
     /**
      * 
      * @param mixed $group
      * @return mixed
      */
 
     private function groupper($group){
         $s = "GROUP BY";
                foreach($group as $grup){
                    
                    $s .= $grup.",";
                    
                }
                
                return rtrim($s,",");
         
     }
     
     /**
      *
      * 3 değer alan diziyi 2 e düşürür
      * @param $array
      * @return array
      *
      */
     
     private function returnTheQueryArrayForTheGenerate(array $array = []){
         
         $return = [];
         foreach($array as $key){
             
           $return[$key[0]] = $key[2];
             
         }
         
     }

     /**
      * 
      * @param array $array
      * @return string
      */

     private function wherer(array $array = [])
     {
         
         $s = " AND ";
     
         $end = ' AND ';
        if(count($array) > 0){
            
            
            foreach($array as $key){
                
                
                // foreach parçalama başladı
                
           
                // standart where
                 if(count($key) == 1){
                 
                    $arrays = $this->getStringWithStringAndEndStringsFromArrayAndReturnArray($key, "'", "'",'value'); 
           
                  
                    $s .= $this->databaseWhereQueryBuilder($arrays, '', '=', $end);
                 
                     
                 }
                 
                 //
                 
                 if(count($key) == 3){
                     
                     $startKey = $this->getStringWithStringAndEndStrings($key[0], "'", "'");
                     $endKey = $this->getStringWithStringAndEndStrings($key[2], "'", "'");
                     
                     $queryArray = $this->returnTheQueryArrayForTheGenerate($key);
                     $s = $this->databaseWhereQueryBuilder($queryArray, '', $key[1], ' AND ');
                     
                 }
                
                //
                
                
                
            }
            
            $s = ltrim($s, $end);
            
            
            if(count($this->or_where[$this->selectedTable])){
                
                 if($s != ''){
                     
                     
                     $bas = ' OR ';
                     
                 }else{
                     
                     $bas = '';
                     
                 }
                 
                 $or = "";
                 
                 foreach($this->or_where as $orkey){
                     
                     $orvkey = $this->returnTheQueryArrayForTheGenerate($orkey);
                 
                     $or .= $this->databaseWhereQueryBuilder($orvkey, '', $orvkey[1], ' OR ');                         
                     
                
                 }
                 $s = rtrim($s, " AND ");
                 $or = $bas.$or;
                 $or = rtrim($or, " OR ");
                
            }else{
                
                $s = rtrim($s, " AND ");
                
            }
            
            $s .= $or;
      
      
        }
       
        return $s;
 
     }
 
 
     /**
      * @param array $array
      * @return string
      */
 
     private function liker(array $likes)
     {
 
         $like = '';
 
         foreach($likes as $array)
         {
 
             
             foreach (  $array as $likeKey => $likeValue)
             {
    
                 $like .= $likeKey.' LIKE %'.$this->connector->quote($likeValue).'% AND ';
             }
 
         }
 
         if(is_array($this->specialLike[$this->selectedTable]))
         {
 
             $like .= $this->specialLiker($this->specialLike[$this->selectedTable]);
 
         }else{
 
             $like = rtrim($like, 'AND ');
 
         }
 
 
         return $like;
 
     }
 
     private function specialLiker($like)
     {
 
         $msg = '';
 
         foreach($like as $l)
         {
 
             $li = $this->connector->quote($l[2]);
             $msg .= "{$l[0]} LIKE '{$l[1]}{$li}{$l[3]}, ";
 

         }
 
         return rtrim($msg, ",");
 
     }

     private function orderer(array $order = null){

         if($order !== null){
             
                    $s = '';

         return "ORDER BY {$order[0]} {$order[1]}";
             
         }
  

     }
 
     /**
      * @param $join
      * @return string
      *
      *  [ 'INNER JOIN' => [
      *    'yazarlar','id','id'
      *   ]
      *
      */
 
     private function joiner($joinArray)
     {
         $val = "";
         foreach($joinArray as $join)
         {
 
             foreach($join as $joinKey => $joinVal)
             {
                 $val .= $joinKey.' '.$joinVal[0].' ON '.$joinVal[0].'.'.$joinVal[1].' = '.$this->selectedTable.'.'.$joinVal[2];
             }
 
         }
 
 
         return $val;
     }
 
     /**
      * Fetch Yapmak i�in Kullan�r se�ilen moda g�re i�lem yap�lacak t�r� belirler
      * @param unknown $query
      * @param unknown $mode
      * @return mixed|boolean
      */
 
     private function fetcher( $query , $mode )
     {
 
          
 
         if($query instanceof PDOStatement)
         {
 
              
             switch($mode){
 
                 case 'OBJ':
 
                     $fetch = $query->fetch(static::FETCH_OBJ);
 
                     break;
 
                 case 'ASSOC':
 
                     $fetch = $query->fetch(static::FETCH_ASSOC);
 
                     break;
 
                 case 'NUM':
 
                     $fetch  = $query->fetch(static::FETCH_NUM);
 
                     break;
 
                 case 'BOTH':
 
                     $fetch = $query->fetch(static::FETCH_BOTH);
 
                     break;
 
                 default:
 
                     $fetch = $query->fetch(static::FETCH_OBJ);
 
                     break;
 
             }
 
             $this->lastFetch = $fetch;
             return $fetch;
 
         }else{
 
             $query =  $this->query($query);
 
             return $this->fetcher($query, $mode);
 
         }
 
 
     }
 
     /**
      * 
      * sorgudaki limit query sini yapar
      * @param $limit
      * @return string
      */
 
     public function limiter($limit)
     {
         
         if(count($limit) > 0){
             
        $limitbaslangic = $limit[0];
 
         $return = $limitbaslangic;
 
         if(isset($limit[1]))
         {
             $limitson = $limit[1];
             $return .= ','.$limitson.' ';
         }
 
         return $return;
 
             
         }
        
 
 
     }
 
     /**
      * @param array $select
      * @return string
      */
 
     public function getter( $getter)
     {
 
         $s = '';
         if (is_array($getter) && count($getter) > 0) {
             $this->databaseWhereQueryBuilderOnlyKey($getter, '', ',');
         } else {
             return "*";
         }
 
     }
 
     /**
      *
      * ��erik ekleme i�inde kullan�l�r
      *
      */
 
     public function create()
     {
         $table = $this->selectedTable;
 
         $msg = ' INSERT INTO '.$this->selectedTable.' SET '.$this->databaseWhereQueryBuilder($this->set[$table],'','=',', ');
 
         return $this->query( $msg );
 
     }
 
     /**
      * @return PDOStatement
      * ��erik G�ncelleme ��leminde Kullan�l�r
      */
 
     public function update()
     {
         $table = $this->selectedTable;
         $where = $this->where[$table];
         $msg = ' UPDATE '.$table.' SET '.$this->databaseWhereQueryBuilder($this->set[$table],'','=',', ').' WHERE '.$this->wherer($where);
         return $this->query( $msg );
     }
 
     /**
      * @return PDOStatement
      * Silme ��leminde kullanl�r
      */
 
     public function delete()
     {
         $table = $this->selectedTable;
         $where = $this->where[$table];
         $msg = 'DELETE FROM '.$table.' WHERE '.$this->wherer($where);
         return $this->query( $msg );
     }
 
     /**
      * Se�ilenleri birle�tirip okuma i�lemi yapar
      * @param string $fetch
      * @return boolean
      */
 
     public function read($fetch = true)
     {
 
         $table = $this->selectedTable;
         $where = $this->where[$table];
         $like  = $this->like[$table];
         $join  = $this->join[$table];
         $limit = $this->limit[$table];
         $with =  $this->with[$table];
         $order = $this->order[$table];
         $group = $this->group[$table];
         //where baslangic
         $groupString = '';
         if(is_array($where))
         {
             $where = $this->wherer($where);
         }
         // where son
 
         // like baslangic
         if(is_array($like) && count($like) > 0)
         {
             $like =   $this->liker($like);
         }
         
         if(is_array($group) && count($group) > 0){
             
             $groupString = $this->groupper($group);
             
         }
         // like son
 
         //join baslangic
 
         if(is_array($join) && !$with && !is_string($with) )
         {
             $join = $this->joiner($join);
         }elseif( $with && is_string($with) ){
 
             $join = $this->wither($with,$where);
 
         }

         if(is_array($order) && count($order) > 0){

             
             $order = $this->orderer($order[0]);

         }
 
         //join son
 
         //select baslangic
 
         $select = $this->getter($this->get[$table]);
 
         //select son
 
         //limit ba�lang��
 
         $limit =  $this->limiter($limit);
 
 
 
         //limit son
 
         $msg = 'SELECT '.$select.' FROM '.$table.' ';
         
         
         if($groupString !== ''){
             
             $msg .= $groupString.' ';
             
         }
 
         
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
 
         if(isset($order) && is_string($order)){

             $msg .= $order;

         }
 
         if ( isset($limit ) && !is_array($limit) )
         {
             $msg .= ' LIMIT '.$limit;
         }
 
         $query = $this->query($msg);
 
         $this->lastQuery;
 
 
         $this->lastQueryString = $msg;
 
 
         echo $msg;
 
         if($query && $query instanceof PDOStatement)
         {
 
             $this->lastSucessQuery = $query;
 
 
             if($fetch === true) {


                 $return =  $this->fetcher( $query, $this->fetchName);
                 $this->flush();
                 return $return;
 
             }else{
 
                 return $this;
 
             }
 
         }else{
 
 
 
             $error = $this->errorInfo();
 
             $this->lastError = $error;
 
             $this->lastErrorString = $error[2];
 
             return false;
 
         }
 
 
 
     }
     
     /**
      * 
      * Tablodaki herşeyi döndürür
      * @return type
      */
     
     public function all(){
         
         $table = $this->selectedTable;
         
         $query = "SELECT * FROM $table";
         
         $query = $this->query($query);
         
         return $query->fetchAll();
         
     }
     
     
     /**
      * Query sorgusunu döndürür
      * @return type
      */
 
     public function returnQuery()
     {
 
         if(isset($this->lastSucessQuery))
         {
 
             return  $this->lastSucessQuery;
 
         }
 
     }
 
     /**
      * Hata mesajını döndürür
      * @return boolean|string
      */
 
     public function returnErrorString()
     {
 
         if($this->lastErrorString)
         {
 
             return $this->lastErrorString;
 
         }else{
 
             return false;
 
         }
 
 
     }
 
     /**
      * Son oluşan hatayı döndürür
      * @return mixed
      */
 
     public function returnError()
     {
 
         if($this->lastError)
         {
             return $this->lastError;
         }else{
 
             return false;
 
         }
 
 
     }
 
     /**
      * ��eri�i temizlemek i�in
      * @return NULL
      */
 
     public function flush()
     {

         $this->where = [];
         $this->specialWhere = [];
         $this->specialLike = [];
         $this->limit = [];
         $this->join = [];
         $this->set = [];
         $this->get = [];
         $this->or_where = [];
         $this->special_or_where = [];
         $this->order = [];
         return $this;

     }
 
     /**
      * Otomatik sayfalama i�lemi i�in kullan�l�r
      * @see Pagination
      * @param unknown $parse
      * @param PDOStatement $query
      * @return \Myfc\DB\Creator\Pagination
      */
     public function pagination($parse,PDOStatement $query = null)
     {
 
         $pagination = Pagination::boot($parse);
 
         $records = 0;
 
         if($query !== null){
 
             $records = $query->rowCount();
 
         }else{
 
             if($this->lastSucessQuery instanceof PDOStatement)
             {
                  
                 $records = $this->lastSucessQuery->rowCount();
                  
             }
 
         }
 
 
         $pagination->setRecords($records);
 
         $activePage = $pagination->getStartPage();
 
         $limit = $pagination->getCount();
 
         $this->limit($activePage,$limit);
 
          
 
         return $pagination;
 
     }
 
 
      
     public function downloadExcel($fileName = 'MyfcDB', $fetch = null)
     {
 
          
          
 
         if($fetch === null)
         {
              
             $fetch = $this->lastSucessQuery;
              
         }
          
 
         while($f = $fetch->fetch(static::FETCH_OBJ))
         {
              
              
             $arr = (array) $f;
 
             $array[] = $arr;
 
 
         }
          
         $excel = new Excel();
          
         $keys = array_keys($array);
          
         $values = array_values($array);
          
         $excel->setTableNames($keys)->setFileName($fileName);
          
         $excel->setTableValues($values);
         return $excel;
          
     }
 
     public function downloadXml($fileName = 'MyfcDB', $fetch = null)
     {
         if($fetch === null)
         {
              
             $fetch = $this->lastSucessQuery;
              
         }
 
         while($f = $fetch->fetch(static::FETCH_OBJ))
         {
              
              
             $arr = (array) $f;
 
             $array[] = $arr;
 
 
         }
          
         $excel = new Excel($this->selectedTable);
 
         $excel->setType('xml');
          
         $keys = array_keys($array);
          
         $values = array_values($array);
          
         $excel->setTableNames($keys)->setFileName($fileName);
          
         $excel->setTableValues($values);
          
         return $excel;
          
 
     }
 
     /**
      * Kay�t say�s�n� d�nd�r�r
      * @return number|boolean
      */
     public function count()
     {
 
         if($this->lastSucessQuery)
         {
 
             return $this->lastSucessQuery->rowCount();
 
         }else{
 
             return false;
 
         }
 
     }
 
 
     private function checkFetchValue($name)
     {
 
 
         if($this->lastFetch)
         {
 
             if(is_array($this->lastFetch))
             {
 
                 return (isset($this->lastFetch[$name])) ? $this->lastFetch[$name]:false;
 
             }
 
             if($this->lastFetch instanceof stdClass)
             {
 
                 return ($this->lastFetch->$name) ? $this->lastFetch->$name:false;
 
             }
 
         }else{
 
             $this->fetcher($this->lastSucessQuery, 'OBJ');
 
         }
 
     }
 
 
     /**
      * Dinamik olarak �a��rma i�lemi
      * @param unknown $name
      * @param unknown $params
      * @return mixed
      */
 
     public function __call( $name, $params )
     {
 
         if(method_exists($this->connector, $name) || is_callable([$this->connector, $name]))
         {
 
             return call_user_func_array([$this->connector, $name], $params);
 
         }else{
 
             return false;
 
         }
 
     }
 
     
 
     public function __get($name)
     {
 
         return $this->checkFetchValue($name);
 
     }
      
 
 }

?>