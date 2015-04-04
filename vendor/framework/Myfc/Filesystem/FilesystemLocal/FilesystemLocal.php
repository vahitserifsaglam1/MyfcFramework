<?php
 namespace Myfc\Filesystem;
/**
 *FileSystem Object
 *
 *
 *
 *
 *  @package    file-system
 *  @version    1.0
 */


Class FilesystemLocal {

    public static $instance;

    public $dirs;

    protected $mode;

    protected static $scanAll;

    public function __construct() {}


    public static function getInstance()
    {
        if (!isset(self::$instance))
        {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function getName()
    {

        return "FilesystemLocal";

    }

    public function getExtension($filename)
    {

        $fileParts = explode(".",$filename);
        return end($fileParts);


    }
    public  function publicImg()
    {
        $scan = $this->scan(PUBLIC_PATH.'img/*.{png,jpg,gif}',GLOB_NOSORT);

        return $scan;
    }
    public  function publicJs()
    {
        $scan = $this->scan(PUBLIC_PATH.'js/*.js',GLOB_NOSORT);

        return $scan;
    }
    public  function publicCss()
    {
        $scan = $this->scan(PUBLIC_PATH.'css/*.css',GLOB_NOSORT);

        return $scan;
    }
    public function in($dirs)
    {
        $resolvedDirs = array();

        foreach ((array) $dirs as $dir) {
            if (is_dir($dir)) {
                $resolvedDirs[] = $dir;
            } elseif ($glob = glob($dir, GLOB_BRACE | GLOB_ONLYDIR)) {
                $resolvedDirs = array_merge($resolvedDirs, $glob);
            } else {
                throw new \InvalidArgumentException(sprintf('The "%s" directory does not exist.', $dir));
            }
        }

        $this->dirs = array_merge($this->dirs, $resolvedDirs);

        return $this;
    }
    public  function scanType($path,$type)
    {
        $pattern = $path.".{$type}";
        return glob($pattern,GLOB_BRACE);
    }
    public  function Scan($path,$type = GLOB_NOSORT,$realpath = false)
    {
        $pattern = glob($path,$type);
        if($realpath) {
            $pattern = array_map('realpath',$pattern);
        }
        return $pattern;
    }

    public function exists($path)
    {

        return ( file_exists($path ) ) ? true:false;

    }

    public static function scanAll( $path = false )
    {

        if( !$path )
        {
            $path = APP_PATH.'Configs/';
        }



        $search = glob( $path, GLOB_NOSORT);

        foreach ( $search as $key )
        {

            if(!is_dir( $key ) )
            {
                static::$scanAll[] = $key;
            }else{

                static::$scanAll[] = static::scanAll($key);

            }

            return static::$scanAll;

        }

    }
    public function Read($filename,$remote=false){
        if (!$remote){
            if (file_exists($filename)){
                $handle = fopen($filename, "r");
                $content = fread($handle, filesize($filename));
                fclose($handle);
                return $content;
            }
            else {return "The specified filename does not exist";}
        }
        else{

            $content = file_get_contents($filename);
            return $content;
        }

    }

    public function Write($data,$filename,$append=false){
        if (!$append){$mode="w";} else{$mode="a";}
        if($handle = fopen($filename,$mode)){
            fwrite($handle, $data);
            fclose($handle);
            return true;
        }
        return false;
    }

    public function createDirectory($path)
    {
        $this->mkdir($path);
    }

    public function Create($path)
    {


        if(!file_exists($path))
        {

            touch($path);

        }

        return $path;

    }

    public  function Mkdir($path) {
        $path = str_replace("\\", "/", $path);
        $path = explode("/", $path);

        $rebuild = '';
        foreach($path AS $p) {

            // Check for Windows drive letter
            if(strstr($p, ":") != false) {
                $rebuild = $p;
                continue;
            }
            $rebuild .= "/$p";
            //echo "Checking: $rebuild\n";
            if(!is_dir($rebuild)) mkdir($rebuild);
        }

        return true;
    }

    public function Delete($src){
        if(is_dir($src) && $src != ""){
            $result = $this->Listing($src);

            // Bring maps to back
            // This is need otherwise some maps
            // can't be deleted
            $sort_result = array();
            foreach($result as $item){
                if($item['type'] == "file"){
                    array_unshift($sort_result, $item);
                }else{
                    $sort_result[] = $item;
                }
            }

            // Start deleting
            while(file_exists($src)){
                if(is_array($sort_result)){
                    foreach($sort_result as $item){
                        if($item['type'] == "file"){
                            @unlink($item['fullpath']);
                        }else{
                            @rmdir($item['fullpath']);
                        }
                    }
                }
                @rmdir($src);
            }
            return !file_exists($src);
        }else{
            @unlink($src);
            return !file_exists($src);
        }
    }
    function Copy($src, $dest){

        // If source is not a directory stop processing
        if(!is_dir($src)) return false;

        // If the destination directory does not exist create it
        if(!is_dir($dest)) {
            if(!mkdir($dest)) {
                // If the destination directory could not be created stop processing
                return false;
            }
        }

        // Open the source directory to read in files
        $i = new \DirectoryIterator($src);
        foreach($i as $f) {
            if($f->isFile()) {
                copy($f->getRealPath(), "$dest/" . $f->getFilename());
            } else if(!$f->isDot() && $f->isDir()) {
                $this->copy($f->getRealPath(), "$dest/$f");
            }
        }
    }

    function Move($src, $dest){

        // If source is not a directory stop processing
        if(!is_dir($src)) {
            rename($src, $dest);
            return true;
        }

        // If the destination directory does not exist create it
        if(!is_dir($dest)) {
            if(!mkdir($dest)) {
                // If the destination directory could not be created stop processing
                return false;
            }
        }

        // Open the source directory to read in files
        $i = new \DirectoryIterator($src);
        foreach($i as $f) {
            if($f->isFile()) {
                rename($f->getRealPath(), "$dest/" . $f->getFilename());
            } else if(!$f->isDot() && $f->isDir()) {
                $this->move($f->getRealPath(), "$dest/$f");
                @unlink($f->getRealPath());
            }
        }
        @unlink($src);
    }

    public function Listing($path) {
        $arr = array();
        if(is_dir($path)) {
            // Open the source directory to read in files
            $i = new DirectoryIterator($path);
            foreach($i as $f) {
                if(!$f->isDot())
                    $arr[] = $f->getFilename();
            }
            return $arr;
        }
        return false;
    }
    public function rmdirContent($path) {
        // Open the source directory to read in files
        $i = new \DirectoryIterator($path);
        foreach($i as $f) {
            if($f->isFile()) {
                unlink($f->getRealPath());
            } else if(!$f->isDot() && $f->isDir()) {
                rmdir($f->getRealPath());
            }
        }

    }
    public function Remove($path) {
        if(is_dir($path)) {
            rmdir($path);
        } else {
            unlink($path);
        }
    }


    public function findByExtension($path, $ext){
        $arr = array();
        $files = $this->listing($path);

        foreach ($files as $f) {
            $info = pathinfo($path . "/$f");
            if(isset($info['extension']) && $info['extension'] == $ext)
                $arr[] = $path . "/$f";
        }
        return $arr;
    }

}

?>

