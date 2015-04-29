<?php 
 
  namespace Myfc\Session\Connector;
  
  use Myfc\Filesystem;
  use Symfony\Component\Finder\Finder as Finder;
  
  class file{
  
      public $filesystem;
  
      public $options;
  
      public $filepath;
  
      public $lifetime;
  
  
      public function  __construct( array $options )
      {
  
          $this->filesystem = Filesystem::boot('Local');
  
          $this->options = $options;
  
          $this->filepath = $options['file']['path'].DIRECTORY_SEPARATOR;
          
          $this->gc();
  
      }
  
      public function set($name,$value,$time)
      {
  
          $create =   $this->filesystem->Create($this->filepath.$name);
  
          $this->filesystem->Write($value,$create);
  
  
      }
  
      public function get($name)
      {
  
          if($this->filesystem->exists($this->filepath.$name))
          {
  
              $this->filesystem->Read($this->filepath.$name);
  
          }
  
      }
      public function delete($name)
      {
  
          $this->filesystem->Delete($this->filepath.$name);
  
      }
  
      public function flush()
      {
  
          $this->filesystem->rmdirContent($this->filepath);
  
      }
  
      public function gc($lifetime)
      {
          $files = Finder::create()
          ->in($this->path)
          ->files()
          ->ignoreDotFiles(true)
          ->date('<= now - '.$lifetime.' seconds');
  
          foreach ($files as $file)
          {
              $this->filesystem->Delete($file->getRealPath());
          }
      }
      
      public function check()
      {
          
          if(!file_exists($this->filepath))
          {
              
              $this->filesystem->mkdir($this->filepath);
              
          }
          
          return true;
          
      }
      
  }

?>