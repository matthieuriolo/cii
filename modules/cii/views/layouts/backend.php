<?php

/* @var $this \yii\web\View */
/* @var $content string */

use cii\helpers\Html;
use cii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;


use cii\widgets\Positions;

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

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Log', 'url' => [Yii::$app->seo->relativeAdminRoute('log')]],
            ['label' => 'Dashboard', 'url' => [Yii::$app->seo->relativeAdminRoute('index')]],
        ],
    ]);

    NavBar::end();
    ?>

    <div class="container">
        <?php echo Breadcrumbs::widget([
            'homeLink' => [
                'label' => Yii::t('app', 'Administrator'),
                'url' => [Yii::$app->seo->relativeAdminRoute('index')]
            ],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]); ?>
        
        <?php
        foreach(Yii::$app->session->getAllFlashes() as $key => $message) {
            echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
        } ?>
        
        <div class="row">
            <div class="col-md-3">
                <div class="list-group list-group-margin-top">
                    <span class="list-group-item">
                        <?php echo Html::textInput(
                            '',
                            '', [
                            'placeholder' => Yii::t('app', 'Filter menu...'),
                            'class' => "form-control",
                            'data-controller' => "filter-nested-menu"
                        ]); ?>
                    </span>
                    
                    <?php
                    foreach(Yii::$app->cii->package->all(true) as $pkg) {
                        $items = $pkg->getBackendItems();
                        Html::printNestedMenu($items, function($item) {
                            $itemUrl = Url::toRoute($item['url']);
                            $appUrl = Yii::$app->getRequest()->getUrl();
                            if($itemUrl == $appUrl) {
                                return true;
                            }else {
                                $itemUrl = $item['url'][0];
                                $appUrl = Yii::$app->urlManager->getCalledRoute();
                                
                                if(strpos($itemUrl, '/admin/modules') === 0) {
                                    if(dirname(ltrim($itemUrl, '/')) == dirname($appUrl)) {
                                        return true;
                                    }
                                }
                            }

                            return false;
                        });
                    }
                    ?>
                </div>
            </div>

            <div class="col-md-9">
                <?= $content ?>
            </div>
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
