<?php

use yii\helpers\Html;
use cii\widgets\DetailView;

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'attribute' => 'activate_id',
            'format' => 'html',
            'value' => Html::a($model->activate->slug, [Yii::$app->seo->relativeAdminRoute('route/view'), 'id' => $model->activate->id])
        ],

        [
        	'attribute' => 'redirect_id',
        	'format' => 'html',
        	'value' => $model->redirect_id ? Html::a($model->redirect->slug, [Yii::$app->seo->relativeAdminRoute('route/view'), 'id' => $model->redirect->id]) : null
        ],

        [
        	'attribute' => 'login_id',
        	'format' => 'html',
        	'value' => $model->login_id ? Html::a($model->login->slug, [Yii::$app->seo->relativeAdminRoute('route/view'), 'id' => $model->login->id]) : null
        ],

        [
        	'attribute' => 'forgot_id',
        	'format' => 'html',
        	'value' => $model->forgot_id ? Html::a($model->forgot->slug, [Yii::$app->seo->relativeAdminRoute('route/view'), 'id' => $model->forgot->id]) : null
        ],
    ],
]) ?>
