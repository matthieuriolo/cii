<?php

use cii\grid\GridView;
use cii\grid\ActionColumn;
use cii\helpers\Html;
use yii\widgets\Pjax;


use app\modules\cii\models\Package;

Pjax::begin();

if(!isset($packageRoute)) {
    $packageRoute = 'cii/setting';
}

if(isset($model)) {
    echo $model->render($this);
}

$showExtension = isset($showExtension) ? $showExtension : false;

echo GridView::widget([
    'dataProvider' => $data,
    'tableOptions' => [
        'class' => "table table-striped table-bordered table-hover",
        'data-controller' => 'singlerowclick'
    ],
    'columns' => [
        'label',
        [
            'attribute' => 'extension',
            'format' => 'extension',
            'visible' => $showExtension,
        ],

        'translatedType',
        
        [
            'attribute' => 'default',
            'format' => 'html',
            'value' => 'preparedDefault',
        ],

        [
            'attribute' => 'value',
            'format' => 'html' 
        ],

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'urlCreator' => function($action, $model, $key, $index) use($packageRoute) {
                $params = ['id' => $model->id, 'key' => $model->key, 'type' => $model->extension_type];
                if($action == 'delete') {
                    $route = [
                        \Yii::$app->seo->relativeAdminRoute('modules/' . $packageRoute . '/delete'),
                        $params
                    ];
                }else if($action == 'update') {
                    $route = [
                        \Yii::$app->seo->relativeAdminRoute('modules/' . $packageRoute . '/update'),
                        $params
                    ];
                }

                return \Yii::$app->urlManager->createUrl($route);
            },
        ],
    ],
]);

Pjax::end(); ?>
