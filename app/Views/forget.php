<?php


?>

<div class="container">

    <div class="alert alert-danger">

        Şifrenizi eposta adresinize göndereceğiz Lütfen eposta adresinizi giriniz

    </div>

    <?php if(isset($message) && $message && !empty($message) ) {

        ?>

        <div class="alert alert-<?php echo $message['class']; ?>">

            <?php echo $message['message']; ?>

        </div>

    <?php

    }


    ?>

    <div class="col-lg-5">
        <div class="form-group"><input name="eposta" class="form-control" type="email" placeholder="Eposta"/></div>

        <div class="form-group"><input class="btn btn-primary" value="Gönder" type="text"/></div>
    </div>
</div>