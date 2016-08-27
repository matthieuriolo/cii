<?php

use yii\helpers\Html;
use cii\widgets\DetailView;
use yii\bootstrap\Tabs;


$this->title = Yii::p('cii', 'User') . ' - ' . $model->username;
$this->params['breadcrumbs'][] = [
    'label' => Yii::p('cii', 'Users'),
    'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/user/index')]
];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-view">
    <p class="pull-right">
        <?= Html::a(Yii::p('cii', 'Update'), [Yii::$app->seo->relativeAdminRoute('modules/cii/user/update'), 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::p('cii', 'Send mail'), [Yii::$app->seo->relativeAdminRoute('modules/cii/user/mail'), 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        <?php if(!$model->superadmin) { ?>
            <?php if(Yii::$app->user->getIdentity()->id != $model->id) { ?>
                <?= Html::a(Yii::p('cii', 'Login as'), [Yii::$app->seo->relativeAdminRoute('modules/cii/user/switch'), 'id' => $model->id], ['class' => 'btn btn-default']) ?>
            <?php } ?>
            <?= Html::a(Yii::p('cii', 'Delete'), [Yii::$app->seo->relativeAdminRoute('modules/cii/user/delete'), 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::p('cii', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php } ?>
    </p>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php 
    $items = [
        [
            'encode' => false,
            'label' => '<i class="glyphicon glyphicon-question-sign"></i> ' . Yii::p('cii', 'Information'),
            'content' => $this->render('_overview', ['model' => $model]),
        ]
    ];

    if(!$model->superadmin) {
        $items[] = [
            'encode' => false,
            'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::p('cii', 'Groups'),
            'content' => $this->render('_groups', [
                'data' => $groups,

                'model' => $groupModel,
                'groups' => $availableGroups,
                'groupOptions' => $groupOptions
            ]),
        ];
    }else { ?>
        <div class="alert alert-warning">
            <?= Yii::p('cii', 'This user is the superadmin!'); ?>
        </div>
    <?php } ?>
    <?= Tabs::widget(['items' => $items]); ?>
</div>
