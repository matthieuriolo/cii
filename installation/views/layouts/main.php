<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title><?php echo $this->title; ?></title>
    
    <style>
    <?php echo file_get_contents(__DIR__ . '/../../../modules/cii/vendor/bower/bootstrap/dist/css/bootstrap.min.css'); ?>
    </style>

    <?php

    use yii\helpers\Html;
    if(class_exists('Html')) {
        Html::csrfMetaTags();
    }
    ?>
</head>
<body>
<div class="container">
    <header>
        <h1><?php echo $this->title; ?></h1>
    </header>
    <hr>
    <main>
        <?php echo $content; ?>
    </main>
    <hr>
    <footer>
        <p>Server: <?php
        echo isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '' ,
            ' ' ,
            @strftime('%Y-%m-%d %H:%M', time())
        ;
        ?></p>
        <p>Powered by <a href="https://github.com/matthieuriolo/cii" rel="external">Cii</a></p>
    </footer>
</div>
</body>
</html>
