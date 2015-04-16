<?php


use Myfc\Http\Request; 
use Myfc\Facade\Console;
use Myfc\File;

Console::addFunction('get', function($url){
	 
	 $file =  new File();
	
	 $request = new Request();
	 
	 $get = $request->get($url);
	 
	 $body =  $get->getBody();
	 
	 $file->create("test.html");
	 
	 $file->write("test.html",$body);
	
});