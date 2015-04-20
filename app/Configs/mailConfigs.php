<?php
/**
 *  ***********************************
 *
 *
 *   Mailer sınıfında kullanılacak standart ayarlar;
 *
 *
 *    /////////////////////////
 *
 *            Bu ayarları bilmiyorsanız eğer sunucu yöneticinizle iletişime geçiniz
 *
 *   //////////////////////////
 *
 *   -------------------------------------
 *
 *     Aşağıdaki port  ve secure ayarları gmail,hotmail vs uyumludur host,username,password kısımlarını değiştirmeniz yeterlidir
 *
 *   ------------------------------------
 *
 *  ***************************************
 */
 return

     [
	 
	 'mailer' => [
	 
	 'username' => 'asdasd',

         'password' => 'asdasd',

         'port' => 25,

         'host' => 'host',

         'secure' => 'tls',
	 
	 
	 ],
	 
	 'mailgun' => [
	   
	    'domain' => 'your mailgun domain',
		'secret' => 'your mailgun secret'
	   
	 ]


	 


   ];