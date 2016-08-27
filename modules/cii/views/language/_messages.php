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
                'appendixRoute' => 'modules/cii/language'
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
