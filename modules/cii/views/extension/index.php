<?php

use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\data\ArrayDataProvider;
use cii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'Extensions';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php echo Html::a(
    Yii::p('cii', 'Install Extension'),
    [\Yii::$app->seo->relativeAdminRoute('extension/install')],
    ['class' => 'btn btn-success pull-right']
); ?>

<h1><?= Yii::p('cii', 'Extensions'); ?></h1>

<?php 

Pjax::begin();

echo $model->render($this);

echo GridView::widget([
    'tableOptions' => [
        'class' => "table table-striped table-bordered table-hover",
        'data-controller' => 'singlerowclick'
    ],

    'dataProvider' => $data,
    'rowOptions' => function($model, $key, $index, $grid) {
        return $model->name == 'cii' & ($model->package || $model->layout) ? ['class' => "warning"] : [];
    },

    'columns' => [
        'name',
        'installed:datetime',
        'type',
        'enabled:boolean',
        [
            'class' => 'cii\grid\ActionColumn',
            'headerOptions' => ['class' => 'action-column column-width-3'],

            'appendixRoute' => 'extension',
            'template' => '{view} {disable}{enable} {delete}',
            'visibleButtons' => [
                'disable' => function ($model, $key, $index) {
                    if(
                        !$model->enabled
                        ||
                        ($model->name == 'cii' && $model->package)
                        ||
                        ($model->name == 'cii' && $model->layout)
                    ) {
                        return false;
                    }

                    return true;
                },

                'enable' => function ($model, $key, $index) {
                    if(
                        $model->enabled
                        ||
                        ($model->name == 'cii' && $model->package)
                        ||
                        ($model->name == 'cii' && $model->layout)
                    ) {
                        return false;
                    }
                    
                    return true;
                },

                'delete' => function ($model, $key, $index) {
                    if(
                        ($model->name == 'cii' && $model->package)
                        ||
                        ($model->name == 'cii' && $model->layout)
                    ) {
                        return false;
                    }

                    return true;
                }
            ]
        ],
    ],
]);

Pjax::end(); ?>
