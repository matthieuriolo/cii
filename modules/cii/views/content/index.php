<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;


use cii\helpers\Url;
use cii\widgets\PjaxBreadcrumbs;

use app\modules\cii\Permission;

$editable = Yii::$app->user->can(['cii', Permission::MANAGE_CONTENT]) || Yii::$app->user->can(['cii', Permission::MANAGE_ADMIN]);

$this->title = Yii::p('cii', 'Contents');
$this->params['breadcrumbs'][] = $this->title;
if($pjaxid) {
    Pjax::begin([
        'id' => $pjaxid,
    ]);

    echo PjaxBreadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        'pjaxid' => $pjaxid,
    ]);
}
?>


<div class="content-index">
    <?php if($editable) { ?>
        <?= Html::a(Yii::p('cii', 'Create Content'), [
            \Yii::$app->seo->relativeAdminRoute('content/create')
            ] + ($pjaxid ? ['pjaxid' => $pjaxid] : []), [
                'class' => 'btn btn-success pull-right'
            ]) ?>
    <?php } ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <p class="lead"><?= Yii::p('cii', 'Contents define what data can be accessed (usually through routes)'); ?></p>

    <?php Pjax::begin(); ?>
        
        <?= $model->render($this); ?>

        <?= GridView::widget([
            'tableOptions' => [
                'class' => "table table-striped table-bordered table-hover" . ($pjaxid ? ' table-select': ''),
                'data-controller' => $pjaxid ? 'dataselect' : 'singlerowclick',
            ],
            
            'rowOptions' => function($model, $key, $index, $grid) {
                return [
                    'data-value' => $model->id,
                    'data-url' => Url::to([
                        Yii::$app->seo->relativeAdminRoute('content/view'),
                        'id' => $model->id
                    ]),
                    'data-name' => Html::encode($model->name),
                ];
            },

            
            'dataProvider' => $dataProvider,
            'columns' => [
                'name',
                [
                    'attribute' => 'classname',
                    'value' => 'classname.typename',
                ],
                
                'created:datetime',
                'enabled:boolean',

                [
                    'class' => 'cii\grid\ActionColumn',
                    'visibleButtons' => [
                        'update' => $editable,
                        'delete' => $editable,
                    ],
                    'headerOptions' => ['class' => 'action-column ' . ($editable ? 'column-width-3' : 'column-width-1')],
                    'buttonOptions' => $pjaxid ? ['data-pjax' => '#' . $pjaxid] : [],

                    'appendixRoute' => 'content',
                    'optionsRoute' => $pjaxid ? ['pjaxid' => $pjaxid] : [],
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>
<?php
if($pjaxid) {
    Pjax::end();
}
?>