<?php 
            $options = require "../public.php";

            $opt = $options['appPath'].'Configs/databaseConfigs.php';

           class api extends pdo{
               public $type;
               public $select;
               public $where;
               public $like;
               public $oauth;
               public $order;
               public $ordertype;
               public $from;
               public $sahip;
               public $limit;
               public function __construct($get)
               {
                   global  $opt;

                   $opt = $opt['Connections']['mysql'];

                   extract($opt);

                   parent::__construct("mysql:host=$host;dbname=$dbname", $username, $password);
                   parent::query("SET NAMES UTF8");
                   extract($get);
                   if($consumer_key && $consumer_secret && $oauth_token && $oauth_token_secret)
                   {
                   $kontrol = parent::query("SELECT * FROM oauth WHERE consumerkey = '$consumer_key' and consumersecret = '$consumer_secret'"); // uyguluma anahtarı sorgulaması
                   $kont = parent::query("SELECT * FROM user WHERE oauth_token = '$oauth_token' and oauth_token_secret='$oauth_token_secret'"); // kullanıcı tokeni sorgulaması
                   if($kontrol->rowCount() && $kont->rowCount() ){
                   parent::query("SET NAMES UTF8");
                   $sahip = $kontrol->fetch(PDO::FETCH_OBJ);
                   $permission = $sahip->permission;
                   if (@$type) {
                       $this->type = $type;
                   }
                   if (@$consumer_key) {
                       $this->oauth = $consumer_key;
                   }
                   if($consumer_secret)
                     { 
                       $this->consumersecret = $consumer_secret;
                     }
                   
                   if (@$where) {
                       $where = ltrim($where, "|");
                       $where = rtrim($where, "|");
                       $where = explode("|", $where);
                       $d = "";
                       foreach ($where as $key) {
                           $key = explode("/", $key);
                           $d .= $key[0] . "='" . $key[1] . "' AND";
                       }

                       $where = rtrim($d,"AND");
                       $this->where = " WHERE ".$where;
                   }
                   if (@$from) {
                       $this->from = $from;
                   }
                   if (@$select) {
                       $this->select = $select;
                   }
                   if (@$like) {
                       $this->like = $like;
                   }
                   if (@$order) {
                       $this->order = $order;
                   }
                   if (@$ordertype) {
                       $this->ordertype = $ordertype;
                   }
                       if(@$limit){
                           $this->limit = $limit;
                       }
                  
                      switch ($type) {
                          case 'SELECT':
                              include "class/select.php";
                              $this->apiQuery = selectApi::createQuery($this);
                              break;
                          case 'UPDATE':
                          if($permission >0)
                          {
                              include "class/update.php";
                              $this->apiQuery = updateApi::createQuery($this);
                          }
                          else{
                            $this->apiQuery = false;
                            print_r(json_encode(array('errors' => "Yetkiniz buna izin vermiyor")));
                          }

                              break;
                          case 'DELETE':
                            if($permission == 2)
                             {
                               include "class/delete.php";
                                $this->apiQuery = deleteApi::createQuery($this);
                             }
                             else{
                              $this->apiQuery = false;
                                print_r(json_encode(array('errors' => "Yetkiniz buna izin vermiyor")));
                              }

                              
                              break;
                          case 'INSERT':
                          if($permission > 0)

                          {
                                                          include "class/insert.php";
                              $this->apiQuery = insertApi::createQuery($this);
                          }
                          else{
                            $this->apiQuery = false;
                            print_r(json_encode(array('errors' => "Yetkiniz buna izin vermiyor")));
                          }

                              break;
                      }
                      @$yap = parent::query($this->apiQuery);
                       if($yap != false )
                       {
                           if($yap->rowCount())
                           {
                               print_r(json_encode($yap->fetch(PDO::FETCH_ASSOC)));
                           }
                           else{
                           print_r(json_encode(array('errors' => parent::errorInfo())));
                       }

                       }
                       if($yap == false )
                       {

                       }
                       
                   }
                   else{
                       print_r(json_encode(array('errors' => "Uyuşmayan doğrulama anahtarı")));
                   }

                   }
                   else{
                       print_r(json_encode(array('errors' => "Doğrulama anahtarı bulunamadı")));
                   }

 }

                   }

   if($_GET)
   {
        $api = new api($_GET);
   }else{
       print_r(json_encode(array('errors' => "HERHANGİ BİR PAREMETRE GÖNDERİLMEDİ")));
   }
?>