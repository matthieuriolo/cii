<?php

use yii\helpers\Html;
use cii\widgets\DetailView;

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'redirect:route',
        'register:route',
        'forgot:route',
        'captcha:route',
        'remember_visible:boolean',
    ],
]) ?>
