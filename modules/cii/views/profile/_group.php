<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $data,
        'columns' => [
            'group.name',
            'created:datetime'
        ],
    ]); ?>
<?php Pjax::end(); ?>