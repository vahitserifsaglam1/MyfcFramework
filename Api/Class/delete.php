<?php 	                       class deleteApi {

                            public static  function createQuery($thiss){
                                 $query = "DELETE FROM ".$thiss->from." ".$thiss->where;
                                 return $query;
                            }
}
                             ?>