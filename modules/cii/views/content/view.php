<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use cii\helpers\SPL;

$this->title = $model->name;
$this->params['breadcrumbs'][] = [
    'label' => Yii::p('cii', 'Contents'),
    'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/content/index')
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


?>
<div class="content-view">
    <p class="pull-right">
        <?= Html::a(Yii::p('cii', 'Update'), [Yii::$app->seo->relativeAdminRoute('modules/cii/content/update'), 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::p('cii', 'Delete'), [Yii::$app->seo->relativeAdminRoute('modules/cii/content/delete'), 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::p('cii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Tabs::widget([
            'items' => $items
        ]);
    ?>
</div>
