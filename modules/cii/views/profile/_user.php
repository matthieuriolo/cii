<?php

use yii\helpers\Html;
use cii\widgets\DetailView;

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'username',
        'email:email',
        'created:datetime',
        [
        	'attribute' => 'language_id',
        	'value' => $model->language_id ? $model->language->name : null,
        	'visible' => Yii::$app->cii->package->setting('cii', 'multilanguage')
        ],

        'layout_id',
    ],
]);