<?php
use yii\helpers\Json;
use cii\helpers\Html;
use cii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\layouts\cii\assets\AppAsset;


use cii\widgets\BackendMenu;
use cii\widgets\Pjax;

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

<div class="homepage-background"><?= $this->renderShadows('background'); ?></div>

<div class="wrap">
    <?php

    $label = '<div class="brand-label pull-right">' . Yii::$app->cii->package->setting('cii', 'name') . '</div>';

    if($logo = Yii::$app->cii->layout->setting('cii', 'logo')) {
        $label = '<div class="brand-logo pull-left">' .
            Html::img(Url::base(true) . '/' . $logo) . 
            '</div>' .
            (Yii::$app->cii->layout->setting('cii', 'onlylogo') ? '' : $label)
        ;
    }


    NavBar::begin([
        'brandLabel' => $label,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top'
        ],
    ]);

    if($this->isAdminArea() || !Yii::$app->cii->package->setting('cii', 'offline')) {
        echo $this->renderShadows('navbar');

        if($this->isAdminArea() && !Yii::$app->user->isGuest) {
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Log', 'url' => [Yii::$app->seo->relativeAdminRoute('log')]],
                    ['label' => 'Dashboard', 'url' => [Yii::$app->seo->relativeAdminRoute('index')]],
                    ['label' => 'Documentation', 'url' => [Yii::$app->seo->relativeAdminRoute('doc')]],
                    ['label' => 'Logout', 'url' => [Yii::$app->seo->relativeAdminRoute('logout')]],
                ],
            ]);
        }
    }

    NavBar::end();
    ?>

    <div class="container">
        <?= $this->renderShadows('before_main'); ?>

        <noscript>
            <p class="alert alert-danger"><?= Yii::l('cii', 'Your browser does not seem to support the required JavaScript configuration'); ?></p>
        </noscript>


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

                if(count($leftContents) || ($this->isAdminArea() && !Yii::$app->user->isGuest)) {
                    $countMiddle -= 3;
                ?>
                    <div class="col-md-3">
                        <?php
                        if($this->isAdminArea() && !Yii::$app->user->isGuest) {
                            echo BackendMenu::widget();
                        }

                        foreach($leftContents as $c) {
                            echo $this->renderShadow($c, 'left');
                        }
                        ?>
                    </div>
                <?php } ?>

                <main class="col-md-<?= (string)$countMiddle; ?>">
                    <?= $this->renderShadows('inner_main'); ?>

                    <?php
                    if($this->getIsPjax()) {
                        Pjax::begin([
                            'id' => $this->pjaxid()
                        ]);
                    }

                    echo $content;
                    
                    if($this->getIsPjax()) {
                        Pjax::end();
                    }
                    ?>

                    <?= $this->renderShadows('outer_main'); ?>
                </main>

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

        <?= $this->renderShadows('after_main'); ?>
    </div>
</div>

<footer class="footer">

    <div class="container">
        <?php
        $footer = $this->getContents('footer');

        if(count($footer)) {
                foreach($footer as $c) {
                    echo $this->renderShadow($c, 'footer');
                }
        } ?>

        <?php if(Yii::$app->cii->layout->setting('cii', 'show_copyright')) { ?>
            <p class="pull-right"><?= Yii::powered() ?></p>
            <p class="pull-left"><?= Yii::$app->cii->powered() ?></p>
        <?php } ?>

        <div class="text-center social-buttons">
            <i class="fa fa-google-plus-square" data-controller="buttons/google"></i>
            <i class="fa fa-facebook-square" data-controller="buttons/facebook"></i>
            <i class="fa fa-twitter-square" data-controller="buttons/twitter"></i>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>

<style>
    .navbar {
        background-color: <?= Yii::$app->cii->layout->setting('cii', 'navbarcolor'); ?>;
        color: <?= Yii::$app->cii->layout->setting('cii', 'navbarcolor_text'); ?>;
    }

    .navbar a {
        color: <?= Yii::$app->cii->layout->setting('cii', 'navbarcolor_link'); ?>;
    }

    .navbar a:hover,
    .navbar a:focus {
        color: <?= Yii::$app->cii->layout->setting('cii', 'navbarcolor_link_hover'); ?>;
    }


    .footer {
        background-color: <?= Yii::$app->cii->layout->setting('cii', 'footercolor'); ?>;
        color: <?= Yii::$app->cii->layout->setting('cii', 'footercolor_text'); ?>;
    }

    .footer a {
        color: <?= Yii::$app->cii->layout->setting('cii', 'footercolor_link'); ?>;
    }

    .footer a:hover,
    .footer a:focus {
        color: <?= Yii::$app->cii->layout->setting('cii', 'footercolor_link_hover'); ?>;
    }
</style>

</body>
</html>
<?php $this->endPage() ?>
