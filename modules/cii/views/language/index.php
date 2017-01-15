<?php

use yii\helpers\Html;
use cii\grid\GridView;
use yii\widgets\Pjax;


$this->title = Yii::p('cii', 'Languages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pull-right">
    <?= Html::a(Yii::p('cii', 'Install Message'), [\Yii::$app->seo->relativeAdminRoute('language/install')], ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::p('cii', 'Create Language'), [\Yii::$app->seo->relativeAdminRoute('language/create')], ['class' => 'btn btn-success']) ?>
</div>

<h1><?= Html::encode($this->title) ?></h1>
    
<?php Pjax::begin(); ?>
    <?= $model->render($this); ?>
    <?= GridView::widget([
        'tableOptions' => [
            'class' => "table table-striped table-bordered table-hover",
            'data-controller' => 'singlerowclick'
        ],
        
        'dataProvider' => $data,

        'columns' => [
            'name',
            'code',
            'shortcode',
            'created:datetime',
            'enabled:boolean',
            
            [
                'class' => 'cii\grid\ActionColumn',
                'headerOptions' => ['class' => 'action-column column-width-4'],
                'appendixRoute' => 'language',
                'template' => '{view} {update} {enable}{disable} {delete}',
                'visibleButtons' => [
                    'enable' => function($model, $key, $index) {
                        return !$model->enabled;
                    },

                    'disable' => function($model, $key, $index) {
                        return $model->enabled;
                    },
                ],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
