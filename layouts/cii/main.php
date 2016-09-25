<?php
use yii\helpers\Json;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\layouts\cii\assets\AppAsset;


use cii\widgets\BackendMenu;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <?php if($favicon = Yii::$app->cii->layout->setting('cii', 'favicon')) { ?>
        <link rel="shortcut icon" href="<?php
        echo Yii::$app->request->baseUrl , '/' , $favicon;
        ?>" type="image/x-icon" />
    <?php } ?>

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="homepage-background"><?php
$background = $this->getContents('background');
foreach($background as $c) {
    echo $this->renderShadow($c, 'background');
}
?></div>

<div class="wrap">
    <?php

    $label = '<div class="brand-label pull-right">' . Yii::$app->cii->package->setting('cii', 'name') . '</div>';

    if($logo = Yii::$app->cii->layout->setting('cii', 'logo')) {
        //$label .= '<div class="brand-logo pull-left" style="background-image: url(' . $logo . ')"></div>';
        $label = '<div class="brand-logo pull-left">' .
            Html::img($logo) . 
            '</div>' .
            (Yii::$app->cii->layout->setting('cii', 'onlylogo') ? '' : $label)
        ;
    }


    NavBar::begin([
        'brandLabel' => $label,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
            'style' => 'background-color: ' . Yii::$app->cii->layout->setting('cii', 'navbarcolor') . ';'
        ],
    ]);

    if($this->isAdminArea()) {
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => 'Log', 'url' => [Yii::$app->seo->relativeAdminRoute('log')]],
                ['label' => 'Dashboard', 'url' => [Yii::$app->seo->relativeAdminRoute('index')]],
            ],
        ]);
    }else {
        foreach($this->getContents('navbar') as $c) {
            echo $this->renderShadow($c, 'navbar');
        }
    }

    NavBar::end();
    ?>

    <div class="container">
        <?php if(!$this->isAdminArea() && Yii::$app->cii->package->setting('cii', 'offline')) {
            echo Yii::$app->cii->package->setting('cii', 'offline_description');
        }else { ?>
            <?php if(Yii::$app->cii->layout->setting('cii', 'show_breadcrumb') || $this->isAdminArea()) {
                echo Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]);
            } ?>
            
            <?php
            foreach(Yii::$app->session->getAllFlashes() as $key => $message) {
                echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
            } ?>

            <div class="row"><?php
                $countMiddle = 12;
                $leftContents = $this->getContents('left');
                $rightContents = $this->getContents('right');
                
                if(count($rightContents)) {
                    $countMiddle -= 3;
                }

                if(count($leftContents) || $this->isAdminArea()) {
                    $countMiddle -= 3;
                ?>
                    <div class="col-md-3">
                        <?php
                        if($this->isAdminArea()) {
                            echo BackendMenu::widget();
                        }

                        foreach($leftContents as $c) {
                            echo $this->renderShadow($c, 'left');
                        }
                        ?>
                    </div>
                <?php } ?>

                <div class="col-md-<?= (string)$countMiddle; ?>">
                    <?= $content ?>
                </div>

                <?php
                if(count($rightContents)) {
                ?>
                    <div class="col-md-3">
                        <?php
                        foreach($rightContents as $c) {
                            echo $this->renderShadow($c, 'right');
                        }
                        ?>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-right"><?= Yii::powered() ?></p>
        <p class="pull-left"><?= Yii::$app->cii->powered() ?></p>

        <div class="text-center social-buttons">
            <i class="fa fa-google-plus-square" data-controller="buttons/google"></i>
            <i class="fa fa-facebook-square" data-controller="buttons/facebook"></i>
            <i class="fa fa-twitter-square" data-controller="buttons/twitter"></i>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>

<style>
    .navbar-inverse .navbar-brand,
    .navbar-inverse .navbar-nav > li > a {
        color: <?= Yii::$app->cii->layout->setting('cii', 'navbarcolor_text'); ?>;
    }

    .navbar-inverse .navbar-brand:hover,
    .navbar-inverse .navbar-brand:focus,
    .navbar-inverse .navbar-nav > li > a:hover,
    .navbar-inverse .navbar-nav > li > a:focus {
        color: <?= Yii::$app->cii->layout->setting('cii', 'navbarcolor_text_hover'); ?>;
    }
</style>

</body>
</html>
<?php $this->endPage() ?>
