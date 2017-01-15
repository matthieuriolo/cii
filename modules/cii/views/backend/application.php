<?php
use cii\helpers\Html;
use cii\helpers\Url;

$this->title = Yii::p('cii', 'Application');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1><?= Html::encode($this->title); ?></h1>
    <p class="lead"><?= Yii::p('cii', 'Additional functions and informations of the application'); ?></p>

    <?php 
    echo $this->render('_application_information');
    ?>

    <div class="row">
        <div class="col-md-6"><b><?php
        echo Yii::p('cii', 'Last backup:'), ' ';

        $file = Yii::$app->basePath . '/web/backup.zip';
        if(file_exists($file)) {
            echo Html::a(
                Yii::$app->formatter->asDatetime(filemtime($file)),
                Url::base(true) . '/backup.zip'
            );
        }else {
            echo Yii::$app->formatter->asText(null);
        }
        ?></b></div>
        <div class="col-md-6"><?php echo Html::a(
            Yii::p('cii', 'Create backup'),
            [\Yii::$app->seo->relativeAdminRoute('createbackup')],
            ['class' => 'btn btn-default']
        ); ?>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-6"><?php echo Html::a(
            Yii::p('cii', 'Clear Cache'),
            [\Yii::$app->seo->relativeAdminRoute('flushcache')],
            ['class' => 'btn btn-default btn-bottom-padding']
        ); ?>
        </div>

        <div class="col-md-6"><?php echo Html::a(
            Yii::p('cii', 'Clear logs'),
            [\Yii::$app->seo->relativeAdminRoute('flushlog')],
            ['class' => 'btn btn-default btn-bottom-padding']
        ); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6"><?php echo Html::a(
            Yii::p('cii', 'Clear thumbnails'),
            [\Yii::$app->seo->relativeAdminRoute('flushthumbnail')],
            ['class' => 'btn btn-default btn-bottom-padding']
        ); ?>
        </div>

        <div class="col-md-6"><?php echo Html::a(
            Yii::p('cii', 'Clear route statistics'),
            [\Yii::$app->seo->relativeAdminRoute('flushroutestatistics')],
            ['class' => 'btn btn-default btn-bottom-padding']
        ); ?>
        </div>
    </div>
</div>
