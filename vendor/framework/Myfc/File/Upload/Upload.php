<?php


 namespace Myfc\File;


 class Upload
 {


     protected $uploadsPath;

     protected $allowedTypes;

     protected $ins;

     protected $newFileName;

     protected $maxsize;

     protected $file;

     protected $name;

     protected $type;

     protected $filePath;

     protected $tmpName;

     protected $errorInfo;

     protected $returnPath;

     protected $blockedExt = [
         '.php','.html',
     ];

     public function __construct( array $file = [], $uploadsPath ='public/files/uploads', $maxsize = null )
     {

         $this->file = $file;

         $this->uploadsPath = $uploadsPath;

         $this->name = $this->file['name'];

         $this->type = $this->file['type'];

         $this->tmpName = $this->file['tmp_name'];

         $this->maxsize = $maxsize;


     }

     public function setMaxSize( $maxsize = null )
     {

         $this->maxsize = $maxsize;

         return $this;

     }

     public function addBlockedExt( $ext = '.js' )
     {

         $this->blockedExt[] = $ext;

         return $this;

     }

     public function setNewFileName( $newFileName = 'uploaded' )
     {

         $this->newFileName = $newFileName;

         return $this;

     }

     public function setUploadsPath( $path = 'Public/files/upload' )
     {

         $this->uploadsPath = $path;
         return $this;

     }


     public static function boot(  array $file = [], $uploadsPath = 'Public/files/upload', $maxsize = null )
     {

         if( !static::$ins )
         {

             static::$ins =  new static($file, $uploadsPath, $maxsize);

         }

         return static::$ins;

     }



     public function uploadFile()
     {

         $checkExt = $this->checkExt();

         $checkFileSize = $this->checkFileSize();

         $checkUploadsPath = $this->checkUploadsPath();

         $createNewFileName = $this->createNewFileName();

         if( $checkExt && $checkFileSize && $checkUploadsPath && $createNewFileName )
         {

           return   $this->uploader($createNewFileName);

         }else{

             return false;

         }




     }

     protected function checkExt()
     {

         $name = $this->name;

         $rastlandi = true;

         foreach( $this->blockedExt as $key )
         {

             if( strstr($name,$key))
             {

                 $rastlandi = false;

                 break;

             }

         }

         return $rastlandi;

     }

     protected function  checkFileSize()
     {

        if($this->maxsize === null )
        {

            return true;

        }else{

          return ( $this->size < $this->maxsize ) ? true:false;

        }

     }

     protected function checkUploadsPath()
     {

         if( !file_exists( $this->uploadsPath ) )
         {

             \Filesystem::boot('Local')
                 ->createDirectory($this->uploadsPath);

         }

         return true;

     }

     public function returnPath()
     {

        return ( $this->returnPath ) ? $this->returnPath:false;

     }

     protected function createNewFileName()
     {

         if( isset ( $this->newFileName ) && $this->newFileName && $this->newFileName !== null )
         {

           if ( $this->checkExt() ) $filename = $this->newFileName;

         }else {
             $filename = $this->name;
         }

         return $filename;

     }

     protected function  uploader( $fileName = null )
     {
         $uploadsPath = $this->uploadsPath;

         if( $fileName === null )
         {

             $fileName = $this->name;

         }

         $name = $uploadsPath.'/'.$fileName;

         if( !file_exists($name) )
         {

             if(move_uploaded_file($this->tmpName,  $name))
             {
                 $this->returnPath = $name;
                 return $name;
             }
             else{
                 $this->errorInfo = "Php tarafında bir hata oluştu";
                 return false;
             }

         }



     }







 }