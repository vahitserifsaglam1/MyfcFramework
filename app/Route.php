<?php

 use Myfc\Facade\Route;
 
 Route::get('/', function(){
    
     $test = "title test";
     return view('index', compact('test'));
     
 });