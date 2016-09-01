<?php


$this->title = Yii::$app->cii->package->setting('cii', 'name');
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Cii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="<?php echo Yii::$app->urlManager->createUrl(['admin']); ?>">Cii Administration</a></p>
    </div>
</div>
