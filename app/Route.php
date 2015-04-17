<?php

 use Myfc\Facade\Route;
 use Myfc\Html\Pagination;
 
 Route::get('/', function(){
     
     $pagination = new Pagination("index/{sayfa}","sayfa");
     $parse = $pagination->execute();
     
 });