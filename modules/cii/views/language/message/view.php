<?php

use yii\helpers\Html;
use cii\widgets\DetailView;
use yii\bootstrap\Tabs;
use yii\widgets\ActiveForm;

$reflection = $model->getReflection();

$this->title = Yii::p('cii', 'Language Message') . ' - ' . $reflection->getDisplayName();
$this->params['breadcrumbs'][] = [
    'label' => Yii::p('cii', 'Languages'),
    'url' => [\Yii::$app->seo->relativeAdminRoute('modules/cii/language/index')]
];

$this->params['breadcrumbs'][] = [
    'label' => Yii::p('cii', 'Language') . ' - ' . $model->language->name,
    'url' => [\Yii::$app->seo->relativeAdminRoute('modules/cii/language/view'), 'id' => $model->language_id]
];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="language-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p class="lead"><?= $reflection->getDescription(); ?></p>

    <?php
        echo Tabs::widget([
            'items' => [
                [
                    'encode' => false,
                    'label' => '<i class="glyphicon glyphicon-question-sign"></i> ' . Yii::p('cii', 'Information'),
                    'content' => $this->render('/extension/_info', ['model' => $model]),
                    //'active' => true
                ],

                [
                    'encode' => false,
                    'label' => '<i class="glyphicon glyphicon-cog"></i> ' . Yii::p('cii', 'Settings'),
                    'content' => $this->render('/setting/_list', [
                        'data' => $settings,
                        'packageURL' => [Yii::$app->seo->relativeAdminRoute('modules/cii/language/message')],
                    ]),
                    'headerOptions' => [
                        'class' => count($settings->allModels) ? '' : 'disabled'
                    ]
                ],
            ]
        ]);?>
</div>
