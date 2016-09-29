<?php

use yii\helpers\Html;
use cii\widgets\DetailView;
use yii\widgets\ActiveForm;

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        [
            'attribute' => 'classname',
            'value' => $model->classname->typename
        ],

        'enabled:boolean',
        'columns_count:integer',
        'created:datetime',
    ],
]); ?>