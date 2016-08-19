<?php
use cii\helpers\Html;
use cii\widgets\DetailView;

$outbox = $model->outbox();

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'slug',
        'enabled:boolean',
        [
        	'attribute' => 'classname',
            'value' => $model->classname->typename
        ],

        'title',
        'created',
        
        [
        	'attribute' => 'language_id',
        	'format' => 'html',
        	'value' => empty($model->language_id) ? '-' : Html::a($model->language->name, [Yii::$app->seo->relativeAdminRoute('modules/cii/language/view'), 'id' => $model->language->id])
        ],

        [
        	'attribute' => 'breadcrumb',
        	'format' => 'html',
        	'value' => Html::a($model->getBreadcrumbs(), ['//'.$model->getBreadcrumbs()])
        ],
    ],
]) ?>
