<?php
use yii\bootstrap\Tabs;
use cii\helpers\Html;

$this->title = 'Package - ' . $package->getName();

/*
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Packages'),
    'url' => [\Yii::$app->seo->relativeAdminRoute('modules/cii/package/index')]
];
*/

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-index">
    <h1>Package - <?php echo $package->getName(); ?></h1>
    <p class="lead"><?php echo $package->getDescription(); ?></p>

    <div class="body-content">

        <?php
        echo Tabs::widget([
            'items' => [
                [
                    'encode' => false,
                    'label' => '<i class="glyphicon glyphicon-question-sign"></i> Information',
                    'content' => $this->render('pkginfo', ['package' => $package]),
                    //'active' => true
                ],

                [
                    'encode' => false,
                    'label' => '<i class="glyphicon glyphicon-cog"></i> Settings',
                    'content' => $this->render('pkgconfig', ['package' => $package, 'data' => $settings]),
                    'headerOptions' => [
                        'class' => $package->isInstalled() ? '' : 'disabled'
                    ]
                ],
            ]]);
        ?>
    </div>
</div>
