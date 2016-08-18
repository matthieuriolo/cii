<?php

use yii\helpers\Html;
use cii\widgets\DetailView;
use yii\bootstrap\Tabs;


$this->title = 'User - ' . $model->username;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Users'),
    'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/user/index')]
];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-view">
    <?php if(!$model->superadmin) { ?>
        <p class="pull-right">
            <?= Html::a(Yii::t('app', 'Update'), [Yii::$app->seo->relativeAdminRoute('modules/cii/user/update'), 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), [Yii::$app->seo->relativeAdminRoute('modules/cii/user/delete'), 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php } ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php 
    $items = [
        [
            'encode' => false,
            'label' => '<i class="glyphicon glyphicon-question-sign"></i> Information',
            'content' => $this->render('_overview', ['model' => $model]),
        ]
    ];

    if(!$model->superadmin) {
        $items[] = [
            'encode' => false,
            'label' => '<i class="glyphicon glyphicon-user"></i> Groups',
            'content' => $this->render('_groups', [
                'data' => $groups,

                'model' => $groupModel,
                'groups' => $availableGroups,
                'groupOptions' => $groupOptions
            ]),
        ];
    }else { ?>
        <div class="alert alert-warning">
            <?= Yii::t('app', 'This user is the superadmin!'); ?>
        </div>
    <?php } ?>
    <?= Tabs::widget(['items' => $items]); ?>
</div>
