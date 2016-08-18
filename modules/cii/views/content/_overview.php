<?php

use yii\helpers\Html;
use cii\widgets\DetailView;

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        'enabled:boolean',
        'created',
        
        [
            'attribute' => 'classname',
            'value' => $model->classname->typename
        ],
    ],
]) ?>