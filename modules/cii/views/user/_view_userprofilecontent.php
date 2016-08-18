<?php

use yii\helpers\Html;
use cii\widgets\DetailView;

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
        	'attribute' => 'show_groups',
        	'format' => 'boolean',
        	
        ],
    ],
]) ?>
