<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

Pjax::begin(); ?>
    <?= GridView::widget([
        'tableOptions' => [
            'class' => "table table-striped table-bordered table-hover",
            'data-controller' => 'singlerowclick'
        ],
        
        'dataProvider' => $data,

        'columns' => [
            'translatedExtension.name',
            'translatedExtension.type',
            'installed:datetime',
            'enabled:boolean',
            
            [
                'class' => 'cii\grid\ActionColumn',
                'appendixRoute' => 'modules/cii/language/message',
                'template' => '{view} {enable}{disable} {delete}',

                'visibleButtons' => [
                    'enable' => function($model, $key, $index) {
                        return !$model->enabled;
                    },

                    'disable' => function($model, $key, $index) {
                        return $model->enabled;
                    },
                ]
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
