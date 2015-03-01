
<?php
     class selectApi{

         public  static function createQuery($thiss)
         {
              $query = $thiss->type." ".$thiss->select." FROM ".$thiss->from;
             if($thiss->where)
             {
                 $query .= $thiss->where;
             }
             if($thiss->like) {
                 $query .= " AND " . $thiss->like;
             }
             if($thiss->order)
             {
                 $query .= " ORDER BY ".$thiss->order." ";
                 if($thiss->ordertype)
                 {
                      $query .= $thiss->ordertype;
                 }
             }
             if($thiss->limit)
             {
                 $query .= " LIMIT ".$thiss->limit;
             }

            return  $query;
         }
     }