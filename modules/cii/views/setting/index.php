<?php

use yii\grid\GridView;
use cii\grid\ActionColumn;
use cii\helpers\Html;

$this->title = 'Settings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1>Settings</h1>
    
   
    <?php 
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
        'default',
        'value',

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}{delete}',

            'urlCreator' => function($action, $model, $key, $index) {
                if($action == 'delete') {
                    $route = [\Yii::$app->seo->relativeAdminRoute('modules/cii/setting/delete'), ['id' => $model->id, 'key' => $model->key]];
                }else if($action == 'update') {
                    $route = [\Yii::$app->seo->relativeAdminRoute('modules/cii/setting/update'), ['id' => $model->id, 'key' => $model->key]];
                }

                return \Yii::$app->urlManager->createUrl($route);
            },
        ],
    ],
]) ?>
</div>
