<?php

use yii\helpers\Html;
use cii\widgets\DetailView;

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'username',
        'email:email',
        'created:datetime',
        'activated:datetime',
        'enabled:boolean',

        [
            'attribute' => 'language_id',
            'format' => 'html',
            'value' => empty($model->language_id) ? '-' : Html::a($model->language->name, [Yii::$app->seo->relativeAdminRoute('language/view'), 'id' => $model->language->id]),
            'visible' => Yii::$app->cii->package->setting('cii', 'multilanguage')
        ],

        'layout_id',
        //'password',
        //'reset_token',
        //'activation_token',
    ],
]) ?>