
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Oopps Error</title>
    <style>

        .container{



            background:none repeat scroll 0% 0% rgba(255, 0, 0, 0.3);
            border-radius: 3px;
        }
    </style>
</head>
<body>
     <div class="container">
          <p style="display:inline-block;"><h1>Dikkat 1 Hata Meydana Geldi;</h1></p>
         <div class="header">
             <h3><?php echo $file; ?></h3>Dosyasında<br/>
             <h4>Satır : <?php echo $line; ?></h4>
         </div>
         <div class="content">
             <h4>Hata Mesajı:<?php echo $message; ?></h4>
         </div>
         <div class="footer">
              <h6>Hata Kodu:<?php echo $code; ?></h6>
         </div>
     </div>
</body>
</html>