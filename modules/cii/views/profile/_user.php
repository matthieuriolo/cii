<?php

use yii\helpers\Html;
use cii\widgets\DetailView;

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'username',
        'email:email',
        'created',
        'language_id',
        'layout_id',
    ],
]);