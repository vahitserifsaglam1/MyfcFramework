<?php
 
 namespace Myfc;
 
 
 use Myfc\Singleton;
 use Myfc\Container;
 
 class Route
 {
     
     private $get;
     
     private $delete;
     
     private $post;
     
     private $where;
     
     private $put;
     
     /**
      * Where eklemesi yapar
      * @param unknown $params
      * @param string $check
      * @return \Myfc\Route
      */
     
     public function where($params = array(),$check = null)
     {
         
         if(!is_array($params))
         {
             
             $params = array($params => $check);
             
         }
         
         $this->where[] = $params;
         
         return $this;
         
     }
   /**
    * Get Eklemesi yapar
    * @param unknown $action
    * @param unknown $callback
    * @return \Myfc\Route
    */
     
     public function get($action,$callback)
     {
         
         $this->setCollection('GET', func_get_args() );
         
         return $this;
         
     }
     
     /**
      * Post eklemesi yapar
      * @param unknown $action
      * @param unknown $callback
      * @return \Myfc\Route
      */
     public function post($action,$callback)
     {
         $this->setCollection('POST', func_get_args() );
          
         return $this;
         
     }
     
     /**
      * delete eklemesi yapar
      * @param unknown $action
      * @param unknown $callback
      * @return \Myfc\Route
      */
     
     public function delete($action,$callback)
      {
         $this->setCollection('DELETE', func_get_args() );
          
         return $this;
         
     }
     
     /**
      * Put eklemesi yapar
      * @param unknown $action
      * @param unknown $callback
      * @return \Myfc\Route
      */
     
     public function put($action,$callback)
     {
         $this->setCollection('PUT', func_get_args() );
     
         return $this;
          
     }
      
     
     /**
      * Collection ekler
      * @param unknown $type
      * @param unknown $params
      */
     private function setCollection($type,$params)
     {
        
         $params[0] = ($params[0] === "/") ? "index":$params[0];
         
         
         
         switch ($type)
         {
         
             case 'GET':
                 
                 $this->get[] = array(
                      
                     'action' => $params[0],
                      
                     'callback' => $params[1]
                      
                 );
                 
                 break;
                 
                 case 'POST':
                      
                     $this->post[] = array(
                 
                     'action' => $params[0],
                 
                     'callback' => $params[1]
                 
                     );
                      
                     break;
                     
                     case 'DELETE':
                          
                         $this->delete[] = array(
                     
                         'action' => $params[0],
                     
                         'callback' => $params[1]
                     
                         );
                          
                         break;
                         
                         case 'PUT':
                              
                             $this->get[] = array(
                         
                             'action' => $params[0],
                         
                             'callback' => $params[1]
                         
                             );
                              
                             break;
                             
                            default:
                                
                              
                                     
                                    $this->get[] = array(
                                
                                    'action' => $params[0],
                                
                                    'callback' => $params[1]
                                
                                    );
                                     
                               
                                
                                break;
                                
             
         }
            
       
         
     }
     
     /**
      * Collectionları Döndürür
      * @return multitype:NULL
      */
     public function getCollection()
     {
         
         
         return array(

             'GET' => $this->get,
             
             'POST' => $this->post,
             
             'DELETE' => $this->delete,
             
             'PUT' => $this->put,
             
             'WHERE' => $this->where
             
         );
         
     }
     

     
 }

?>