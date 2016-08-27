<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

$this->title = Yii::p('cii', 'Group') . ' - ' . $model->name;
$this->params['breadcrumbs'][] = [
    'label' => Yii::p('cii', 'Groups'),
    'url' => [Yii::$app->seo->relativeAdminRoute('index')]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-view">
    <p class="pull-right">
        <?= Html::a(Yii::p('cii', 'Update'), [Yii::$app->seo->relativeAdminRoute('modules/cii/group/update'), 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::p('cii', 'Delete'), [Yii::$app->seo->relativeAdminRoute('modules/cii/group/delete'), 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::p('cii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <h1><?= Html::encode($this->title) ?></h1>


    <?= Tabs::widget([
            'items' => [
                [
                    'encode' => false,
                    'label' => '<i class="glyphicon glyphicon-question-sign"></i> ' . Yii::p('cii', 'Information'),
                    'content' => $this->render('_overview', ['model' => $model]),
                ],
                
                [
                    'encode' => false,
                    'label' => '<i class="glyphicon glyphicon-warning-sign"></i> ' . Yii::p('cii', 'Permissions'),
                    'content' => $this->render('_permissions', [
                        'data' => $permissions,
                        'model' => $permissionModel,
                        'permissions' => $availablePermissions,
                        'permissionOptions' => $permissionOptions
                    ]),
                ],

                [
                    'encode' => false,
                    'label' => '<i class="glyphicon glyphicon-user"></i> Members',
                    'content' => $this->render('_members', ['data' => $users]),
                ],
            ]
        ]
    ); ?>
</div>
