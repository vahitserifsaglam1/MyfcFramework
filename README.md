# MyfcFramework
Myfc Framework MVC yapısı üzerine kurulmuş, küçük, fakat hızlı ve etkili bir frameworkdur.

## Çoklu Dil Desteği
## Smarty ve Twig template engine desteği
## MVC yapısı
## Konsol uygulaması
## Güçlü sınıflar
## Kolay kurulum


gibi özellikleri barındırmaktadır

Örnek Rötalama
> Route::get('/', function(){ echo "hello wordl"; }); // Örnek bir rötalama sistemi

Örnek Veritabanı sorgusu (user tablosunda username i admin'e eşit olanın bilgileri çeker)
> user::where('username','admin') // örnek bir veritabanı sorgusu

Örnek View oluşturma sistemi(seçtiğiniz template engine'e göre [ 'smarty' ise index.tpl, 'twig' ise index.twig.php , 'php' ise index.php dosyasını çeker) 
> view('index', array('param' => 'value')) || View::make('index', array('param' => 'value') // örnek view oluşturma kodları

Örnek Http Request

> use Myfc\Facade\Request;
>$request = Request::get('http://www.ornekdomain.com');

Örnek Sayfalama
> $pagination = $db->pagination('index/sayfa/{sayfa}');
> echo $pagination;

 

















