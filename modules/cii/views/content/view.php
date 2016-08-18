<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use cii\helpers\SPL;

$this->title = $model->name;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Contents'),
    'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/content/index')
]];
$this->params['breadcrumbs'][] = $this->title;

$outbox = $model->outbox();





$items = [
    [
        'encode' => false,
        'label' => '<i class="glyphicon glyphicon-file"></i> Content',
        'content' => $this->render('_overview', [
            'model' => $model
        ])
    ]
];

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
        <?= Html::a(Yii::t('app', 'Update'), [Yii::$app->seo->relativeAdminRoute('modules/cii/content/update'), 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), [Yii::$app->seo->relativeAdminRoute('modules/cii/content/delete'), 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
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
