<?php
namespace File;


/**
 * Class image
 */
class Image{
    /**
     * @var $image
     * @access private
     */
    private $image;


    public function __construct($image){
        $this->image = $image;
        $this->folder = "uploads";
        $this->size = $image['size'];
        $this->maxsize = (1024 * 4048);
        $this->tmpname = $image['tmp_name'];
        $this->ext = substr($image['name'],-4,4);
        $this->newFileName = md5(uniqid());

    }

    /**
     * @param string $folder
     * @return $this
     */
    public function setFolder($folder = "uploads" ){
        $this->folder = $folder;
        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setNewFileName($name)
    {
        $this->newFileName = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function uploadFile(){
        $image = $this->image;
        if($this->size < $this->maxsize)
        {
            if($this->ext == ".jpg" || $this->ext == ".png" || $this->ext == ".gif")
            {

                $this->filePath = $this->folder."/".$this->newFileName.$this->ext;



                if(file_exists($this->folder))
                {
                    if(move_uploaded_file($this->tmpname, $this->filePath))
                    {
                        return true;
                    }
                    else{
                        $this->errorInfo = "Php tarafında bir hata oluştu";
                        return false;
                    }
                }
                else
                {
                    mkdir($this->folder);
                    chmod($this->folder, 777);
                }

            }
            else
            {
                $this->errorInfo = "Dosyanız izin verilen tiplerden biri değil";
                return false;
            }
        }
        else{
            $this->errorInfo = "Dosyanın Boyutu Belirtilen boyuttan büyük";
            return false;
        }
    }

    /**
     * @return string
     */
    public function returnPath()
    {
        return $this->filePath;
    }

    /**
     * @return bool|$this
     */
    public function copy(){
        $file = $this->folder."/".$this->newFileName."_copied".$this->ext;
        if(file_exists($this->filePath)){
            $copy =  copy($this->filePath,$file);
            $this->filePath = $file;
            return ($copy) ? $this:false;
        }
        else{
            return false;
        }
    }

    /**
     * @param $file
     * @return $this
     */
    public function setFile($file)
    {
        $this->filePath = $file;
        $this->ext = substr($file,-4,4);
        return $this;
    }

    /**
     * @param $width
     * @param $height
     * @return $this
     */
    public  function reSize($width,$height)
    {
        $dosya = $this->filePath;
        list($genislik,$yukseklik) = getimagesize($dosya);

        if(strstr($width,"%")){$width = str_replace("%","",$width); $bolw = ( 100 / $width ) ; $width = ($genislik / $bolw);}
        if(strstr($height,"%")){$height = str_replace("%","",$height); $bolh = (100 / $height);$height = ($yukseklik / $bolh); }

        $hedef = imagecreatetruecolor($width,$height);
        $file = $this->folder."/".$this->newFileName."_resized".$this->ext;
        $this->filePath = $file;
        switch($this->ext)
        {
            case '.png':
                $kaynak = imagecreatefrompng($dosya);
                imagecopyresampled($hedef,$kaynak,0,0,0,0,$width,$height,$genislik,$yukseklik);
                imagepng($hedef,$file,100);
                break;
            case '.jpg':
                $kaynak = imagecreatefromjpeg($dosya);
                imagecopyresampled($hedef,$kaynak,0,0,0,0,$width,$height,$genislik,$yukseklik);
                imagejpeg($hedef,$file,100);
                break;
            case '.gif':
                $kaynak = imagecreatefromgif($dosya);
                imagecopyresampled($hedef,$kaynak,0,0,0,0,$width,$height,$genislik,$yukseklik);
                imagegif($hedef,$file,100);
                break;
        }
        imagedestroy($hedef);
        imagedestroy($kaynak);
        unlink($dosya);
        $this->filePath = $file;
        return $this;

    }

    /**
     * @param $quality
     * @return $this
     */
    public function imageCompress($quality)
    {
        $file_ext = $this->ext;
        $file = $this->filePath;
        $newFile = $this->folder."/".$this->newFileName."_compressed".$file_ext;


        switch($file_ext)
        {
            case '.jpg':
                $img = imagecreatefromjpeg($file);
                imagejpeg($img,$newFile,$quality);
                break;
            case '.png':
                if($quality>10){
                    $quality = ceil(100/10);
                }
                $img = imagecreatefrompng($file);
                imagepng($img,$newFile,$quality);
                break;
            case '.gif':
                $img = imagecreatefromgif($file);
                imagepng($img,$newFile);
                break;
        }
        imagedestroy($img);
        unlink($file);
        $this->filePath = $newFile;
        return $this;
    }

    /**
     * @param int $x
     * @return $this
     */
    public function imageRotate($x = 90)
    {
        $file = $this->filePath;
        $file_ext = $this->ext;
        $newFile = $this->folder."/".$this->newFileName."_rotated".$file_ext;
        switch($file_ext)
        {
            case '.png':
                $kaynak = imagecreatefrompng($file);
                $rotated = imagerotate($kaynak,$x,0);
                imagepng($rotated,$newFile,10);
                break;
            case '.jpg':
                $kaynak = imagecreatefromjpeg($file);
                $rotated = imagerotate($kaynak,$x,0);
                imagejpeg($rotated,$newFile,100);
                break;
            case '.gif':
                $kaynak = imagecreatefromgif($file);
                $rotated = imagerotate($kaynak,$x,0);
                imagegif($rotated,$newFile);
                break;
        }
        imagedestroy($kaynak);
        unlink($file);
        $this->filePath = $newFile;
        return $this;
    }

    /**
     * @param sting $string
     * @return $this
     */
    public function setString($string = "")
    {
        $this->string = $string;
        return $this;
    }
    /**
     * @param int $boyut
     * @param int $sol
     * @param int $yukari
     * @return $this
     */
    public function setImageStringOptions($boyut = 2,$sol = 5,$yukari = 1)
    {
        $this->imageStingSol = $sol;
        $this->imageStringYukari = $yukari;
        $this->imageStringBoyut = $boyut;
        return $this;
    }
    /**
     * @param string $string
     * @return $this
     */

    public function imageString($string = "")
    {
        $sol = ($this->imageStringSol) ? $this->imageStringSol:5;
        $yukari = ($this->imageStringYukari) ? $this->imageStringYukari:1;
        $boyut = ($this->imageStringBoyut) ? $this->imageStringBoyut:2;

        $file = $this->filePath;
        if($string == "")
        {
            $string = $this->string;
            if($string == "")
            {
                $string = "OZSAIMAGECLASS";
            }

        }
        $file_ext = $this->ext;
        $newFile = $this->folder."/".$this->newFileName."_stringed".$file_ext;
        switch($file_ext)
        {
            case '.jpg':
                $kaynak = imagecreatefromjpeg($file);
                $renk = imagecolorallocatealpha($kaynak,255,255,255,50);
                imagestring($kaynak,$boyut,$sol,$yukari,$string,$renk);
                imagejpeg($kaynak,$newFile,100);
                break;
            case '.png':
                $kaynak = imagecreatefrompng($file);
                $renk = imagecolorallocatealpha($kaynak,255,255,255,50);
                imagestring($kaynak,$boyut,$sol,$yukari,$string,$renk);
                imagepng($kaynak,$newFile,10);
                break;
            case '.gif':
                $kaynak = imagecreatefromgif($file);
                $renk = imagecolorallocatealpha($kaynak,255,255,255,50);
                imagestring($kaynak,$boyut,$sol,$yukari,$string,$renk);
                imagegif($kaynak,$newFile);
                break;
        }
        imagedestroy($kaynak);
        unlink($file);
        $this->filePath = $newFile;
        return $this;
    }

}
 ?>
