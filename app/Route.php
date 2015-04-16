<?php

 use Myfc\Facade\Route;
 
 Route::get('/index/{test}/{deneme}', function($test = null,$deneme = null){
     
     echo $test; echo $deneme;
     
 });