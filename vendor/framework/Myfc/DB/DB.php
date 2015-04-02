<?php
namespace Myfc;

 use Exception;
 use PDOStatement;
 use Myfc\Html\Pagination;
 use Myfc\File\Excel;

 /**
  *
  * @author vahitþerif
  *        
  */

class DB
{

    private $selectedTable;
    
    private $where;
    
    private $join;
    
    private $set;
    
    private $get;
    
    private $limit;
    
    private $with;
    
    private $specialWhere;
    
    private $like;
    
    private $specialLike;
    
    private $connector;
    
    private $or_where;
    
    private $special_or_where;
    
    const EXTENDTABLE =  'table';
    
    const CONFIG_PATH = 'app/Configs/databaseConfigs.php';
    
    const FETCH_OBJ = 5;
    
    const FETCH_ASSOC = 2;
    
    const FETCH_NUM = 3;
    
    const FETCH_BOTH = 4;
    
    private $configs = array();
    
    private $lastQuery;
    
    private $lastSucessQuery;
    
    private $lastQueryString;
    
    private  $fetchName;
    
    private $lastFetch;
    
    private $lastError;
    
    private $lastErrorString;
    
    /**
     * Sýnýfýn tutulacaðý static deðiþken
     * @var unknown
     */
    
    private static $instance;
    /**
     *  
     *   Baþlatýcý Fonksiyon
     *   
     *   Seçilen tabloyu parametro olarak atýyabilir yada çaðrýldýðý sýnýftaki isimden veya bir deðiþkenden çekebilirsiniz
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
             
             $this->configs = require static::CONFIG_PATH;
             
         }else{
             
             throw new Exception('veritabaný ayar dosyanýz silinmiþ');
             
         }
         
         $this->connect();

        
    }
    
    /**
     * Static olarak sýnýfý baþlatýr
     * @param string $table
     * @return \Myfc\unknown
     */
    
    public static function boot( $table = '')
    {
        
        if(!static::$instance)
        {
            
            static::$instance = new static($table);
            
        }
        
        return static::$instance;
        
    }
    
    /**
     * Seçilen Tabloyu Deðiþtirir
     * @param string $table
     * @return \Myfc\DB
     */
    
    public function setTable($table = '')
    {
        
        $this->selectedTable = $table;
        
        return $this;
        
    }
    
    /**
     * Veri tabaný baðlantýsý yapar
     * 
     *  Hiçbir veri döndürmez
     */
    private function connect()
    {
        
        $configs = $this->configs;
        
        $this->fetchName = $configs['fetch'];
        
        $connect = $configs['Connection'];
        
                
        if(isset($configs[$connect]))
        {
            
            $selected = $configs[$connect];
            
            $driver = $selected['driver'];
            
            $connectorName = 'Myfc\DB\Connector\\'.$driver;
            
            
            $this->connector = new $connectorName($selected , $connect);
            
        }else{
            
            
            throw new Exception('yanlýþ sürücü seçilmiþ, sürücü ayarlarý'.static::CONFIG_PATH.' dosyasýnda bulunamadý');
            
        }
        
     
     
        
        
    }
    
    /**
     * Where Sorgusu Ekler
     * 
     *  Örnek Kullaným : $db->where( array('id' => 1 ) )
     * 
     * @param array $where
     * @return \Myfc\DB
     */
    
    public function where(  $where = array() )
    {
        
        if(!is_array($where))
        {
            
            $args = func_get_args();
            $where = array($args[0] => $args[1]);
            
        }
        
       
        $this->where[$this->selectedTable][] = $where;
        $this->autoQuery();
        
        return $this;
        
    }
    
    /**
     * Sorguya where tanýmý ekler(or)
     * @param unknown $where
     * @return \Myfc\DB
     */
    
    public function or_where( $where = array() )
    {
        
        if(!is_array($where))
        {
        
            $args = func_get_args();
            $where = array($args[0] => $args[1]);
        
        }
        
         
        $this->or_where[$this->selectedTable][] = $where;
        $this->autoQuery();
        
        return $this;
        
        
    }
    
    public function special_or_where($where = array() )
    {
        
        if(!is_array($where))
        {
            
            $args = func_get_args();
            
            $where = array($args[0] => $args[1]);
            
        }
        
        $this->special_or_where[$this->selectedTable][] = $where;
        
        $this->autoQuery();
        
        return $this;
        
    }
    
    /**
     * Özelleþtirilebilir Where sorgusu Ekler (çoðul ekleme için)
     * 
     *  Örnek Kullaným : $db->addSpecialWhere( array( $id , '<' $ids ), ... )
     *  
     * @param array $array
     * @return \Myfc\DB
     */
    
    public function addSpecialWhere( array $array )
    {
        foreach($array as $key)
        {
        
            $this->specialWhere[$this->selectedTable][] = $key;
        
        }
        
        $this->autoQuery();
        return $this;
    }
    
    /**
     * Özelleþtirilebilir where sorgusu ekler (tekil ekleme için )
     *  
     *   Örnek Kullaným : $db->addSpecial($id,'<',$ids)
     *  
     * @param unknown $ilk
     * @param unknown $orta
     * @param unknown $son
     * @return \Myfc\DB
     */
    
    public function addSpecial($ilk,$orta,$son){
    
        $this->specialWhere[$this->selectedTable][] = func_get_args();
        
        $this->autoQuery();
    
        return $this;
    }
    
    /**
     * Ýçeri çekilecek diðer tabloyu ekler
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
     * Sorguda kullanýlacak parametreleri ekler
     * 
     *  Örnek Kullaným : $db->set( array('id' => 1 ) )
     *  
     * @param array $array
     * @return \Myfc\DB
     */
    
    public function set( Array $array = [] )
    {
    
        foreach ( $array as $key => $value )
        {
    
            $this->addSet( $key, $value );
    
        }
    
        return $this;
    
    }
    
    /**
     * Sorguda çekilecek sutunlarý ekler
     * 
     *  Örnek Kullaným : $db->get(array('column1','column2))
     * 
     * @param array $array
     * @return \Myfc\DB
     */
    
    public function get( Array $array = [] )
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
     *   Örnek Kullaným: $db->join( array ( 'INNER JOIN' => array('kitap','fakulte','id)
     *  
     * @param array $join
     * @return \Myfc\DB
     */
    
    public function join( Array $join = [] )
    {
    
        $this->join[$this->selectedTable][] = $join;
    
        $this->autoQuery();
    
        return $this;
    
    }
    
    /**
     *  
     *  Sorguya Like ekler
     *  
     *   Örnek kullaným :$db->like(array('id' => 5))
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
    
    public function addOrWhere(array $array){
        
        $array = array_push($this->or_where[$this->selectedTable],$array);
        
        $this->or_where[$this->selectedTable] = $array;
        
        return $this;
        
    }
    
    /**
     * 
     *  Örnek Kullaným : $db->addSpecialLike( array( array('%',$id','%'), ...))
     * 
     * Sorguya Özel Like ekler
     * @param array $array
     */
    
    public function addSpecialLike( array $array )
    {
        
        foreach($array as $key){
            
            $this->specialLike[$this->selectedTable][] = $key;
            
        }
        
        $this->autoQuery();
        
        return $this;
        
    }
    
    /**
     * Limit atamasý yapar
     * @param unknown $limit
     * @return \Myfc\DB
     */
    public function limit($limit = array())
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
     *  Örnek Kullaným : $db->specialLike('%',$id,'')
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
    /** Veri tabanýndan veri çekme iþlemi için çekilecek verilerin seçilmesi  */
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

           
            if(!isset($this->set[$this->selectedTable])){
                
                $this->read(false);
                
            }
           
            
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
    
    private function mixer(array $array,$end)
    {
        $s = "";
    
        foreach($array as $key => $value)
        {
            $s .= $key.'='. "'$value'".$end;
        }
    
    
        return rtrim($s,$end);
    
    
    }
    
    private function specialer( array $special = array() )
    {
        $s = "AND ";    
        foreach($special as $array ){
    
            $s .= $array[0].$array[1]."'{$array[2]}' AND ";
    
        }
        return rtrim($s," AND ");
    }
    
    /**
     * 
     * @param $array
     * @return string
     * 
     */
    
    private function wherer($array)
    {
        $s = "";

        foreach($array as $a)
        {
            
            foreach ( $a as $whereKey => $whereValue) {
                $whereValue = $this->connector->quote($whereValue);
                $s .= $whereKey . '=' . "$whereValue AND";
            }
            
            $s =  rtrim($s, " AND ");
            
        }
    
        if(is_array($this->or_where[$this->selectedTable]))
        {
        
            $s .= $this->or_wherer($this->or_where[$this->selectedTable]);
        
        }

        if(is_array($this->specialWhere[$this->selectedTable]))
        {
            $s .= $this->specialer($this->specialWhere[$this->selectedTable]);
            
        }
        
            

        $return = $s;
    
        return $return;
    
    
    }
    
    private function or_wherer(array $array){
        
        $s = " OR ";
        
        foreach($array as $a)
        {
        
            foreach ( $a as $whereKey => $whereValue) {
                $whereValue = $this->connector->quote($whereValue);
                $s .= $whereKey . '=' . "$whereValue OR ";
            }
        
        }
        
        if(is_array($this->special_or_where[$this->selectedTable]))
        {
            
            $s .= $this->special_or_wherer($this->special_or_where[$this->selectedTable]);
            
        }
        
        return rtrim($s, " OR ");
        
        
    }
    
    private function special_or_wherer(array $specialer){
        
        $s = "OR ";
        foreach($specialer as $array ){
        
            $a = $this->connector->quote($array[2]);
            $s .= $array[0].$array[1]."'{$a}' OR ";
        
        }
        return rtrim($s," OR ");
        
        
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
     * Fetch Yapmak için Kullanýr seçilen moda göre iþlem yapýlacak türü belirler
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
              
              return false;
              
          }
        
        
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
    
    public function getter( $getter)
    {
    
        $s = '';
        if (is_array($getter)) {
            foreach ($getter as $selectKey) {
                $s .= $selectKey . ',';
            }
            return rtrim($s, ',');
        } else {
            return "*";
        }
    
    }
    
    /**
     * 
     * Ýçerik ekleme iþinde kullanýlýr
     * 
     */
    
    public function create()
    {
        $table = $this->selectedTable;
    
        $msg = ' INSERT INTO '.$this->selectedTable.' SET '.$this->mixer($this->set[$table],',');
    
        return $this->query( $msg );
    
    }
    
    /**
     * @return PDOStatement
     * Ýçerik Güncelleme Ýþleminde Kullanýlýr
     */
    
    public function update()
    {
        $table = $this->selectedTable;
        $where = $this->where[$table];
        $msg = ' UPDATE '.$table.' SET '.$this->mixer($this->set[$table],', ').' WHERE '.$this->wherer($where);
        return $this->query( $msg );
    }
    
    /**
     * @return PDOStatement
     * Silme Ýþleminde kullanlýr
     */
    
    public function delete()
    {
        $table = $this->selectedTable;
        $where = $this->where[$table];
        $msg = 'DELETE FROM '.$table.' WHERE '.$this->wherer($where);
        return $this->query( $msg );
    }
    
    /**
     * Seçilenleri birleþtirip okuma iþlemi yapar
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
        
        $select = $this->getter($this->get[$table]);
        
        //select son
        
        //limit baþlangýç
        
        $limit =  $this->limiter($limit);
        
        
        
        //limit son
        
        $msg = 'SELECT '.$select.' FROM '.$table.' ';
        
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
        
        $query = $this->query($msg);
        
        $this->lastQuery;
        
        
        $this->lastQueryString = $msg;
        
        
        
        if($query && $query instanceof PDOStatement)
        {
            
            $this->lastSucessQuery = $query;
            

            if($fetch === true) {
            
                 
                return $this->fetcher( $query, $this->fetchName);
            
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
    
    public function returnQuery()
    {
        
        if(isset($this->lastSucessQuery))
        {
            
            return  $this->lastSucessQuery;
            
        }
        
    }
    
    /**
     * Hata mesajýný döndürür
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
     * Son oluþan hatayý döndürür
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
     * Ýçeriði temizlemek için
     * @return NULL
     */
    
    public function flush()
    {
        $this->set = array();
        $this->get = array();
        $this->where = array();
        $this->join = array();
        $this->limit = array();
        $this->like = array();
        $this->specialLike = array();
        $this->specialWhere = array();
        return null;
    }
    
    /**
     * Otomatik sayfalama iþlemi için kullanýlýr 
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
    
    /**
     * Kayýt sayýsýný döndürür
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
    
 
    
    /**
     * Dinamik olarak çaðýrma iþlemi
     * @param unknown $name
     * @param unknown $params
     * @return mixed
     */
    
    public function __call( $name, $params )
    {
        
        if(method_exists($this->connector, $name) || is_callable(array($this->connector, $name)))
        {
            
            return call_user_func_array(array($this->connector, $name), $params);
            
        }else{
            
            return false;
            
        }
        
    }

    /**
     * Dinamik olarak static method çaðýrmasý yapar
     * @param unknown $method
     * @param unknown $parameters
     * @return mixed
     */
    
    public static function __callStatic($method, $parameters)
    {
        $instance = new static;
    
        return call_user_func_array(array($instance, $method), $parameters);
    }
    
    
    
    
    
    
    
    
    
    

    

}

?>