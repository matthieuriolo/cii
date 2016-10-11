<?php

use yii\helpers\Html;
use cii\grid\GridView;
use yii\widgets\Pjax;

$positions = $layout->getPositions();

Pjax::begin(); ?>
    <?= $model->render($this); ?>

    <?= GridView::widget([
        'tableOptions' => [
            'class' => "table table-striped table-bordered table-hover",
            'data-controller' => 'singlerowclick'
        ],
        
        'dataProvider' => $dataProvider,
        

        'columns' => [
            'ordering',
            [
                'attribute' => 'position',
                'value' => function($model) use($positions) {
                    return $positions[$model->position];
                }
            ],
            'route.slug',

            [
                'attribute' => 'language.name',
                'visible' => Yii::$app->cii->package->setting('cii', 'multilanguage')
            ],

            'content:content',
            
            [
                'class' => 'cii\grid\ActionColumn',
                'headerOptions' => ['class' => 'action-column column-width-5'],
                'appendixRoute' => 'modules/cii/position',
                'template' => '{view} {update} {delete} {up} {down}',

                'visibleButtons' => [
                    'up' => function($model) {
                        return $model->previous();
                    },

                    'down' => function($model) {
                        return $model->next();
                    },
                ]
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
