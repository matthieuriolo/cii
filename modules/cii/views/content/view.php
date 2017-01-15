<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\widgets\Pjax;

use cii\helpers\SPL;
use cii\widgets\PjaxBreadcrumbs;


use app\modules\cii\Permission;


$this->title = $model->name;
$this->params['breadcrumbs'][] = [
    'label' => Yii::p('cii', 'Contents'),
    'url' => [Yii::$app->seo->relativeAdminRoute('content/index')
]];
$this->params['breadcrumbs'][] = $this->title;

$outbox = $model->outbox();





$items = [
    [
        'encode' => false,
        'label' => '<i class="glyphicon glyphicon-file"></i> ' . Yii::p('cii', 'Content'),
        'content' => $this->render('_overview', [
            'model' => $model,
        ])
    ],
];

if($outbox->canBeShadowed) {
    $items[] = [
        'encode' => false,
        'label' => '<i class="glyphicon glyphicon-blackboard"></i> ' . Yii::p('cii', 'Positions'),
        'content' => $this->render('_positions', [
            'visibilities' => $visibilities,
            'visibleModel' => $visibleModel,
            'routes' => $routes,
            'positions' => $positions,
            'languages' => $languages,
        ])
    ];
}

$info = $outbox->className();
if(SPL::hasInterface($info, 'app\modules\cii\base\LazyModelInterface') && $info::hasLazyCRUD()) {
    $info = $info::getLazyCRUD();
    
    array_push($items, [
        'encode' => false,
        'label' => $info['controller']->$info['label'](),
        'content' => $info['controller']->$info['view']($outbox),
    ]);
}

$editable = Yii::$app->user->can(['cii', Permission::MANAGE_CONTENT]) || Yii::$app->user->can(['cii', Permission::MANAGE_ADMIN]);

$pjaxid = Yii::$app->request->pjaxid();
if($pjaxid) {
    echo PjaxBreadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
    ]);
}
?>
<div class="content-view">
    <?php if($editable) { ?>
        <p class="pull-right">
            <?= Html::a(Yii::p('cii', 'Update'), [
                Yii::$app->seo->relativeAdminRoute('content/update'),
                'id' => $model->id
            ], [
                'class' => 'btn btn-primary'
            ]) ?>
            <?= Html::a(Yii::p('cii', 'Delete'), [
                Yii::$app->seo->relativeAdminRoute('content/delete'),
                'id' => $model->id
            ], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::p('cii', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php } ?>
    
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Tabs::widget([
        'id' => uniqid(),
        'items' => $items
    ]);
    ?>
</div>