<?php

use yii\helpers\Html;
use cii\widgets\DetailView;
use yii\widgets\ActiveForm;

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        'enabled:boolean',
        'created:datetime',
        
        [
            'attribute' => 'classname',
            'value' => $model->classname->typename
        ],
    ],
]); ?>