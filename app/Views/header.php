<html>
<head>

    <title>MyfcFramework test sound app</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

    <!-- Optional theme -->

    <!-- Latest compiled and minified JavaScript -->


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

 <body>

 <nav class="navbar navbar-default">
     <div class="container-fluid">
         <!-- Brand and toggle get grouped for better mobile display -->
         <div class="navbar-header">
             <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                 <span class="sr-only">Toggle navigation</span>
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
             </button>
             <a class="navbar-brand" href="#">Myfc</a>
         </div>

         <!-- Collect the nav links, forms, and other content for toggling -->
         <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
             <ul class="nav navbar-nav">
                 <li class="active"><a href="#">Anasayfa <span class="sr-only">(current)</span></a></li>
                 <li class="dropdown">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Üyelik İşlemleri <span class="caret"></span></a>
                     <ul class="dropdown-menu" role="menu">
                         <li><a href="user/register">Kayıt</a></li>
                         <li><a href="user/login">Giriş</a></li>
                         <li class="divider"></li>
                         <li><a href="user/forget">Şifremi Unuttum</a></li>

                     </ul>
                 </li>
             </ul>
             <form class="navbar-form navbar-left" role="search">
                 <div class="form-group">
                     <input type="text" class="form-control" placeholder="Search">
                 </div>
                 <button type="submit" class="btn btn-default">Ara</button>
             </form>
             <ul class="nav navbar-nav navbar-right">
                 <li><a href="user/add">Kayıt Yükle</a></li>

             </ul>
         </div><!-- /.navbar-collapse -->
     </div><!-- /.container-fluid -->
 </nav>