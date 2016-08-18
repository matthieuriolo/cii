<?php

use yii\helpers\Html;
use cii\widgets\DetailView;

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
        	'attribute' => 'redirect_id',
        	'format' => 'html',
        	'value' => $model->redirect_id ? Html::a($model->redirect->slug, [Yii::$app->seo->relativeAdminRoute('modules/cii/route/view'), 'id' => $model->redirect->id]) : null
        ],
    ],
]) ?>
