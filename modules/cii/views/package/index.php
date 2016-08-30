<?php

use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\data\ArrayDataProvider;
use cii\helpers\Html;
use yii\widgets\Pjax;

$this->title = Yii::p('cii', 'Packages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <?php echo Html::a(
        Yii::p('cii', 'Install Package'),
        [\Yii::$app->seo->relativeAdminRoute('modules/cii/package/install')],
        ['class' => 'btn btn-success pull-right']
    ); ?>
    
    <h1>Packages</h1>

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
            return $model->name == 'cii' ? ['class' => "warning"] : [];
        },

        'columns' => [
            'name',
            'installed:datetime',
            'enabled:boolean',
            [
                'class' => 'cii\grid\ActionColumn',
                'appendixRoute' => 'modules/cii/package',
                'template' => '{view} {disable}{enable} {delete}',
                'visibleButtons' => [
                    'disable' => function($model, $key, $index) {
                        if(
                            !$model->enabled
                            ||
                            $model->name == 'cii'
                        ) {
                            return false;
                        }

                        return true;
                    },

                    'enable' => function($model, $key, $index) {
                        if(
                            $model->enabled
                            ||
                            $model->name == 'cii'
                        ) {
                            return false;
                        }

                        return true;
                    },

                    'delete' => function($model, $key, $index) {
                        if($model->name == 'cii') {
                            return false;
                        }

                        return true;
                    },
                ],
            ],
        ],
    ]);
    Pjax::end(); ?>
</div>
