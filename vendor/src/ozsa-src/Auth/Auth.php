<?php

/**
 * Class Auth
 *  Myfc Framework Auth sınıfı
 *
 *  Kullanıcılarla ilgili işlem yapmak için kullanılır
 */
    use Http\Request;

    class Auth
    {

        public  $userTable = 'user';

        public static $booted;

        public static $key;

        protected static $boot;

        /**
         * @param string $userTable
         *
         *  Başlatıcı sınıf, seçilecek tabloyu ayarlar, standart olarak User Seçilidir.
         */
        public function __construct( $userTable='user' )
        {
            $this->userTable = $userTable;

            $this->database = Database::boot($this->userTable);

        }

        /**
         * @param string $table
         *  Sonradan Tablo seçilmek istenirse
         */

        public function setTable($table = 'user')
        {
           $this->userTable = $table;
        }

        /**
         * @param string $userTable
         * @return mixed
         *  Static olarak sınıfı başlatmak için kullanılır
         *
         * @see ::boot('user')
         */
        public static function boot( $userTable = 'user' )
        {
            if(!static::$boot)
            {

                static::$boot = new static($userTable);

            }

          return static::$boot;

        }



        private  function creator($array)
        {
             $msg = '';
             $value = array();
          foreach ( $array as $key => $values){
              $msg .= $key.'= ? AND ';
              $value[] = $values;
          }
            return array(
                'key' => rtrim($msg," AND "),
                'value' => $value
            );
        }

        public  function Http_Auth( $metin = "OzsaFramework")

        {

            if (!isset($_SERVER['PHP_AUTH_USER'])) {
                header('WWW-Authenticate: Basic realm="'.$metin.'"');
                header(Request::VERSION_10.' 401 Unauthorized');
                echo 'Doğrulamayı Pas geçtiniz';
                exit;
            } else {
                if( $this->attempt(['email' => $_SERVER['PHP_AUTH_USER'],
                'passoword' => $_SERVER['PHP_AUTH_PW']]))
                {
                  return true;
                }else{
                   return false;
                }

            }
        }

        public function register( Array $array = array() )
        {

            $veri = $this->database
                ->setArray($array)
                ->create();

            return  $veri;


        }

        public  function attempt ( Array $array = array(), $remember = false,$callAble = '')
        {

            $creator = static::creator($array);


              $key = $creator['key'];

              $values = $creator['value'];

              $query = $this->database->prepare("SELECT * FROM $this->userTable WHERE $key");


              $query->execute($values);

               if( $query )
               {

                   if ( $query->rowCount() )
                   {

                       if($remember)
                       {
                           Session::set('login', $key[0]);
                           $time =  \Carbon\Carbon::now()->addHour(5);
                           Cookie::set('login',$key[0], $time);

                       }

                       if( is_callable($callAble) )
                       {

                           $reditect = \Desing\Single::make('\Reditect');

                           $callAble($reditect);

                       }

                       return true;

                   }  else{

                       return false;

                   }
               }else{

                   return false;
               }

            static::$key = $values[0];

        }

        public  function check()
        {
            $get = Session::get('login');

            if( $get )
            {
                 if( isset($get) )
                 {

                       if($get == static::$key )

                       {

                            return true;

                       }


                 }else{
                     return false;
                 }
            }else{
                return false;
            }

        }


        public static function __callStatic( $name, $params )
        {

            $boot = static::boot();

            if($boot)
            {

               return call_user_func_array(array($boot,$name),$params);

            }else{

                throw new Exception(" Sınıf başlatılmamış ");

            }


        }

    }

?>