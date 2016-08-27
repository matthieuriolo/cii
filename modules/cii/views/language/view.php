<?php

use yii\helpers\Html;
use cii\widgets\DetailView;
use yii\bootstrap\Tabs;
use yii\widgets\ActiveForm;

$this->title = Yii::p('cii', 'Language') . ' - ' . $model->name;
$this->params['breadcrumbs'][] = [
    'label' => Yii::p('cii', 'Languages'),
    'url' => [\Yii::$app->seo->relativeAdminRoute('modules/cii/language/index')]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="language-view">
    <p class="pull-right">
        <?= Html::a(Yii::p('cii', 'Update'), [\Yii::$app->seo->relativeAdminRoute('modules/cii/language/update'), ['id' => $model->id]], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::p('cii', 'Delete'), [\Yii::$app->seo->relativeAdminRoute('modules/cii/language/delete'), ['id' => $model->id]], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::p('cii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
        echo Tabs::widget([
            'items' => [
                [
                    'encode' => false,
                    'label' => '<i class="glyphicon glyphicon-question-sign"></i> ' . Yii::p('cii', 'Information'),
                    'content' => $this->render('_overview', ['model' => $model]),
                ],

                [
                    'encode' => false,
                    'label' => '<i class="glyphicon glyphicon-adjust"></i> ' . Yii::p('cii', 'Examples'),
                    'content' => $this->render('_examples', ['model' => $formatterExample]),
                ],
                
                [
                    'encode' => false,
                    'label' => '<i class="glyphicon glyphicon-comment"></i> ' . Yii::p('cii', 'Messages'),
                    'content' => $this->render('_messages', ['data' => $messages]),
                ],
                /*
                [
                    'encode' => false,
                    'label' => '<i class="glyphicon glyphicon-question-sign"></i> Settings',
                    'content' => $this->render('/extension/_config', ['data' => $settings]),
                ],*/
            ]]);
        ?>
</div>
