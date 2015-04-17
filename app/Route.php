<?php

 use Myfc\Facade\Route;
 
 Route::get('/', function(){
    
     view('index',array(),false);
   
 });