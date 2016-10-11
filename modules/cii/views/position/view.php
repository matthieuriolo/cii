<?php

use yii\helpers\Html;
use cii\widgets\DetailView;
use yii\bootstrap\Tabs;
use yii\widgets\ActiveForm;



$this->title = Yii::p('cii', 'Position');
$this->params['breadcrumbs'][] = [
    'label' => Yii::p('cii', 'Positions'),
    'url' => [\Yii::$app->seo->relativeAdminRoute('modules/cii/position/index')]
];
$this->params['breadcrumbs'][] = $this->title;
?>

<p class="pull-right">
    <?= Html::a(Yii::p('cii', 'Update'), [\Yii::$app->seo->relativeAdminRoute('modules/cii/position/update'), ['id' => $model->id]], ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::p('cii', 'Delete'), [\Yii::$app->seo->relativeAdminRoute('modules/cii/position/delete'), ['id' => $model->id]], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => Yii::p('cii', 'Are you sure you want to delete this item?'),
            'method' => 'post',
        ],
    ]) ?>
</p>

<h1><?= Html::encode($this->title) ?></h1>
<?php
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'content:content',
        'position:positionTypes',
        'route:route',
    ],
]) ?>

