<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php

    $label = '<div class="brand-label pull-right">' . Yii::$app->cii->setting('cii', 'name') . '</div>';

    if($logo = Yii::$app->cii->setting('cii', 'logo')) {
        //$label .= '<div class="brand-logo pull-left" style="background-image: url(' . $logo . ')"></div>';
        $label = '<div class="brand-logo pull-left">' .
            Html::img($logo) . 
            '</div>' .
            (Yii::$app->cii->setting('cii', 'onlylogo') ? '' : $label)
        ;
    }

    
    NavBar::begin([
        'brandLabel' => $label,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    /*
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Log', 'url' => [Yii::$app->seo->relativeAdminRoute('log')]],
            ['label' => 'Dashboard', 'url' => [Yii::$app->seo->relativeAdminRoute('index')]],
        ],
    ]);
    */

    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        
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

            if(count($leftContents)) {
                $countMiddle -= 3;
            ?>
                <div class="col-md-3">
                    <?php
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
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-right"><?= Yii::powered() ?></p>
        <p><?= Yii::$app->cii->powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
