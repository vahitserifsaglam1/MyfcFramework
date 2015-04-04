 <?php 

   namespace Myfc\File;
   
   class Excel {
       /**
        * @var string
        */
       public $fileName;
       /**
        * @var string
        */
       public $ext;
       /**
        * @var string
        */
       public $tableNames;
       /**
        * @var string
        */
       public $tableValues;
       /**
        * @var string
        */
       public $type;
   
       const EXCEL = 'excel';
   
       const PDF = 'pdf';
   
       const XML = 'xml';
   
       const WORD = 'word';
       /**
        * @return mixed $this;
        */
       public function __construct($fileName = 'excel_class'){
           $this->fileName = $fileName;
           $this->ext = ".xls";
           $this->tableNames = "";
           $this->tableValues = "";
           $this->type = 'excel';
           return $this;
       }
   
       /**
        * @param string $type
        * @return $this
        */
       public function setType($type = "excel")
       {
           $this->type = $type;
           return $this;
       }
       /**
        * @param string $name
        * @return $this
        */
       public function setFileName($name = "excel_class")
       {
   
           $this->fileName = $name;
           return $this;
       }
   
       /**
        * @param string $ext
        * @return $this
        */
       public function setExt($ext = ".xls")
       {
           $this->ext= $ext;
           return $this;
       }
   
       public function setContent($content)
       {
           $this->content = $content;
           return $this;
       }
   
       public function setTableNames($names = array()){
           $this->tableNames = $names;
           return $this;
       }
   
       /**
        * @param array $values
        * @return $this
        */
       public function setTableValues($values = array()){
           $this->tableValues[] = $values;
           return $this;
       }
   
       /**
        * @return $this;
        */
       public function execute()
       {
           switch ($this->type)
           {
               case 'excel':
   
                   $this->createExcel();
   
                   break;
               case 'word':
                   $this->createWord();
                   break;
               case 'xml':
                   $this->createXml();
                   break;
               case 'pdf':
                   $this->cretePdf();
                   break;
           }
       }
       /**
        *@return $this
        */
       public function createExcel()
       {
           $fileName = $this->fileName . $this->ext;
           header("Content-Type: application/vnd.ms-excel; charset=windows-1254");
           header("Content-type: application/x-msexcel; charset=windows-1254");
           header("Content-Disposition: attachment; filename=$fileName");
           $tableNames = $this->tableNames;
           $tableValues = $this->tableValues;
           $n = "";
           $v = "";
   
           foreach ($tableNames as $key) {
               $n .= "$key \t";
   
           }
           for ($i = 0; $i < count($tableValues[0]); $i++) {
               foreach ($tableValues[0][$i] as $ke) {
   
                   $v .= "$ke \t";
               }
               $v .= "\n";
           }
   
           $n .= "\n";
   
           echo $n.$v;
       }
   
       /**
        * @return $this
        */
       public function createXml()
       {
           $fileName = $this->fileName.".xml";
           header("Content-type: text/xml");
           header("Content-disposition: attachment; filename=$fileName");
           $names = $this->tableNames;
           $fileN = $this->fileName;
           $values = $this->tableValues[0];
   
           echo "<$fileN>\n";
            
           for($i=0;$i<count($values);$i++)
           {
           for($is = 0; $is<count($values[$i]);$is++)
           {
           $key = $names[$is];
               echo "<$key>";
               echo $values[$i][$is];
               echo "</$key> \n";
           }
           }
   
           echo "</$fileN>";
           echo "\n";
           return $this;
   
       }
   
       /**
       * @return mixed $this
       */
       public  function  createWord()
       {
       $content = $this->content;
       if(is_string($content) || is_numeric($content))
       {
       $newFileName = $this->fileName.".doc";
       header("Content-type: application/octet-stream");
       header("Content-Disposition: attachment; filename=$newFileName");
           header("Pragma: no-cache");
                header("Expires: 0");
           echo $content;
       }else{
       $this->error = "içeriğiniz bir sting değil; içerik türü : ".var_dump($content);
       }
       return $this;
   
       }
   
       /**
       * @return $this
       */
       public function createPdf()
       {
       $content = $this->content;
            $newFileName = $this->fileName.".pdf";
       $desc = "ozsaClass";
       if(is_string($content) || is_numeric($content)) {
       $fpdf = new \Myfc\File\Excel\FPDF();
       $fpdf->AddPage();
       $fpdf->SetFont('Arial', 'b', 10);
       $fpdf->Cell(40, 10, $content);
       $fpdf->Output($newFileName,"I");
       }else{
           $this->error = "içeriğiniz bir sting değil; içerik türü : ".var_dump($content);
           }
           return $this;
           }
           }
?>