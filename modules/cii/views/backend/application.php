<?php
use \cii\helpers\Html;

$this->title = Yii::p('cii', 'Application');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1><?= Html::encode($this->title); ?></h1>
    <p class="lead"><?= Yii::p('cii', 'Additional functions of the application'); ?></p>
    
    <hr>

    <div class="row">
        <div class="col-md-6"><b><?php
        echo Yii::p('cii', 'Last backup:'), ' ';

        if(false) {

        }else {
            echo Yii::$app->formatter->asText(null);
        }
        ?></b></div>
        <div class="col-md-6"><?php echo Html::a(
            Yii::p('cii', 'Create backup'),
            [\Yii::$app->seo->relativeAdminRoute('createbackup')],
            ['class' => 'btn btn-success']
        ); ?>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-6"><?php echo Html::a(
            Yii::p('cii', 'Clear Cache'),
            [\Yii::$app->seo->relativeAdminRoute('flushcache')],
            ['class' => 'btn btn-success btn-bottom-padding']
        ); ?>
        </div>

        <div class="col-md-6"><?php echo Html::a(
            Yii::p('cii', 'Clear logs'),
            [\Yii::$app->seo->relativeAdminRoute('flushlog')],
            ['class' => 'btn btn-success btn-bottom-padding']
        ); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6"><?php echo Html::a(
            Yii::p('cii', 'Clear thumbnail'),
            [\Yii::$app->seo->relativeAdminRoute('flushthumbnail')],
            ['class' => 'btn btn-success btn-bottom-padding']
        ); ?>
        </div>
    </div>
</div>
