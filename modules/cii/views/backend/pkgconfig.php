<?php 

use yii\grid\GridView;
use yii\grid\ActionColumn;
use cii\helpers\Html;
?>
<br>

<?php echo GridView::widget([
	'dataProvider' => $data,
	'tableOptions' => [
        'class' => "table table-striped table-bordered table-hover",
        'data-controller' => 'singlerowclick'
    ],

    'columns' => [
        'label',
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
