<?php
 
    namespace Myfc;
    
    use Myfc\Singleton;

   class Filesystem
   {

       public $adapter;


       public $disk;


       public function __construct( $boot  = 'Local' )
       {

            $this->disk = $boot;

            $this->adapter = Singleton::make('\Myfc\Adapter','Filesystem');

            $this->adapter->addAdapter(Singleton::make('Myfc\Filesystem\Filesystem'.$boot));

       }

       /**
        * @param string $boot
        * @return static
        *
        *  static olarak sınıfın başlatılması
        *
        */

       public static function boot( $boot='Local' )
       {
           return new static( $boot );
       }

       /**
        * @param string $boot
        * @return static
        *
        *  Kullanılacak Sınıfın sonradan seçilmesi
        */
       public static function disk( $boot = 'Local' )
       {

           return new static( $boot );

       }

       /**
        * @param $name
        * @param $params
        * @return mixed
        *
        * Düz methodların dinamik yöntemle ağit olduğu sınıfdan static olarak çağrılması
        *
        */

       public static function __callStatic( $name, $params)
       {
           $s = new static();
           return call_user_func_array(array($s->adapter,$name),$params);

       }

       /**
        * @param $name
        * @param $params
        * @return mixed
        *
        *
        */
       public function __call( $name, $params )
       {
           $namef = "Filesystem".$this->disk;
           return call_user_func_array(array($this->adapter->$namef,$name),$params);

       }


   }