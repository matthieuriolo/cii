<?php

use yii\grid\GridView;
use cii\grid\ActionColumn;
use cii\helpers\Html;

echo GridView::widget([
    'dataProvider' => $data,
    'tableOptions' => [
        'class' => "table table-striped table-bordered table-hover",
        'data-controller' => 'singlerowclick'
    ],
    'columns' => [
        'label',
        'id',
        'type',
        
        [
            'attribute' => 'default',
            'format' => 'html',
            'value' => 'preparedDefault',
        ],

        'value',

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}{delete}',

            'urlCreator' => function($action, $model, $key, $index) {
                $params = ['id' => $model->id, 'key' => $model->key];
                if($action == 'delete') {
                    $route = [
                        \Yii::$app->seo->relativeAdminRoute('modules/cii/setting/delete'),
                        $params
                    ];
                }else if($action == 'update') {
                    $route = [
                        \Yii::$app->seo->relativeAdminRoute('modules/cii/setting/update'),
                        $params
                    ];
                }

                return \Yii::$app->urlManager->createUrl($route);
            },
        ],
    ],
]) ?>
