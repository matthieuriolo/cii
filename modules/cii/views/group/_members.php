<?php


use yii\grid\GridView;
use yii\widgets\Pjax;
?>


<?php Pjax::begin(); ?>
<?= GridView::widget([
        'dataProvider' => $data,
        'columns' => [
            'user.username',
        	'created',
            'user.enabled:boolean',
            [
                'class' => 'cii\grid\ActionColumn',
                'template' => '{view}',
                'appendixRoute' => 'group',
                'urlCreator' => function($action, $model, $key, $index) {
                    $route = [\Yii::$app->seo->relativeAdminRoute('user/view'), ['id' => $model['user']['id']]];
                    return \Yii::$app->urlManager->createUrl($route);
                },
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
