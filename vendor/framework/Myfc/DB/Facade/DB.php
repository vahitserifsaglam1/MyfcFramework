<?php
 namespace Myfc\Facade;

 use Myfc\DB as Database;

  class DB
  {
      
  
      public  $instance;
      
      const EXTENDTABLE =  'table';

      
      public function __construct()
      {
           

          $table = get_called_class();
          
          if($table !== false)
          {
              
              $vars = get_class_vars($table);
              
               
              
              if( isset($vars[static::EXTENDTABLE]))
               
              {
                   
                  $selectedTable = $vars[static::EXTENDTABLE];
              
                   
              }else{
                   
                  $selectedTable = $table;
                   
              }
              
              $this->instance = Database::boot($selectedTable);
              
              
          }else{
              
              $this->instance = Database::boot('');
              
          }
           
          
          
           
      }
      
      public static function __callStatic($name , $params)
      {
          
          $instance = new static();
          
          return call_user_func_array(array($instance->instance,$name), $params);
          
          
      }
      
      
  }
 
  

?>