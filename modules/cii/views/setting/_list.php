<?php

use yii\grid\GridView;
use cii\grid\ActionColumn;
use cii\helpers\Html;
use yii\widgets\Pjax;


use app\modules\cii\models\Package;

Pjax::begin();


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
            'attribute' => 'id',
            'format' => 'html',
            'value' => function($model) use($showExtension) {
                if(!$showExtension) {
                    return null;
                }

                $ext = 'app\modules\cii\models\\'. ucfirst($model->extension_type);
                $ext = $ext::find()->joinWith('extension as ext')->where(['ext.name' => $model->id])->one();
                if($ext) {
                    return Html::a($model->id, [Yii::$app->seo->relativeAdminRoute('modules/cii/' . $model->extension_type . '/view'), 'id' => $ext->id]);
                }

                return null;
            },
            'visible' => $showExtension
        ],

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
                $params = ['id' => $model->id, 'key' => $model->key, 'type' => $model->extension_type];
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
]);

Pjax::end(); ?>
