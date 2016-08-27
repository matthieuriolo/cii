<?php

use yii\bootstrap\Tabs;
use yii\grid\ActionColumn;
use yii\data\ArrayDataProvider;
use cii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = $modelType;
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $modelUrl,
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <div class="form-group pull-right">
        <?php echo Html::a(
            Yii::p('cii', 'Cancel'),
            $modelUrl,
            ['class' => 'btn btn-warning']
        ); ?>

        <?php echo Html::submitButton(Yii::p('cii', 'Upload'), ['class' => 'btn btn-success']); ?>
    </div>
   
    <h1>Install <?php echo $modelType; ?></h1>
    <br>
    
    <div class="row">
        <div class="col-md-6">
            <?php echo $form->field($model, 'file')->fileInput(); ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
