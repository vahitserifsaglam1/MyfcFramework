<?php    
                              class insertApi {
                                  public static function createQuery($thiss){
                                       $query = "INSERT INTO ".$thiss->from." SET ";
                                      if($_POST)
                                      {
                                           $d = "";
                                           foreach($_POST as $key => $value)
                                           {
                                                $d .= "$key = '$value',";
                                           }
                                           $insert = rtrim($d,",");
                                          $query .= $insert;

                                         return  $query;
                                      }
                                  }
                              }

 ?>