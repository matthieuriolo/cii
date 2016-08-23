<?php

use cii\widgets\DetailView;

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        'enabled:boolean',
        'created:datetime'
    ],
]) ?>
