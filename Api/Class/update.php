<?php
        class updateApi{
            public static function createQuery($thiss)
            {
                $query = $thiss->type." ".$thiss->from." SET ";
                 if($_POST)
                 {
                     $d = "";
                     foreach ($_POST as $key => $value)
                     {
                         $d .= " $key = '$value',";
                     }
                     $query .= rtrim($d,",");
                     if($thiss->where)
                     {
                         $query .=  " ".$thiss->where;
                         return  $query;
                     }
                 }
            }
        }
 ?>