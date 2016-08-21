<?php

use yii\helpers\Html;
use cii\widgets\DetailView;
use yii\bootstrap\Tabs;
use yii\widgets\ActiveForm;

$this->title = 'Language - ' . $model->name;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Languages'),
    'url' => [\Yii::$app->seo->relativeAdminRoute('modules/cii/language/index')]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="language-view">
    <p class="pull-right">
        <?= Html::a(Yii::t('app', 'Update'), [\Yii::$app->seo->relativeAdminRoute('modules/cii/language/update'), ['id' => $model->id]], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), [\Yii::$app->seo->relativeAdminRoute('modules/cii/language/delete'), ['id' => $model->id]], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
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
                    'label' => '<i class="glyphicon glyphicon-question-sign"></i> Information',
                    'content' => $this->render('_overview', ['model' => $model]),
                    //'active' => true
                ],

                [
                    'encode' => false,
                    'label' => '<i class="glyphicon glyphicon-adjust"></i> Examples',
                    'content' => $this->render('_examples', ['model' => $formatterExample]),
                    //'active' => true
                ],
                
                /*[
                    'encode' => false,
                    'label' => '<i class="glyphicon glyphicon-letter"></i> Messages',
                    'content' => $this->render('_messages', ['data' => $messages]),
                ],

                [
                    'encode' => false,
                    'label' => '<i class="glyphicon glyphicon-question-sign"></i> Settings',
                    'content' => $this->render('/extension/_config', ['data' => $settings]),
                ],*/
            ]]);
        ?>
</div>
