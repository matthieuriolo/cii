<?php

use yii\helpers\Html;
use cii\widgets\Toggler;
?>


<div class="row">
    <div class="col-md-6">
    	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-6">
		<?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
	</div>
</div>

<div class="row">
    <div class="col-md-6">
	    <?= $form->field($model, 'shortcode')->textInput(['maxlength' => true]) ?>
	</div>

	<div class="col-md-6">
		<?= Toggler::widget([
            'model' => $model,
            'property' => 'enabled',
            'form' => $form
        ]); ?>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'time')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'date')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'datetime')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'thousandSeparator')->textInput(['maxlength' => true]) ?>
    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'decimalSeparator')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'decimals')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-6">
        <?= Toggler::widget([
            'model' => $model,
            'property' => 'removeZeros',
            'form' => $form
        ]); ?>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'currencySymbol')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-6">
        <?= Toggler::widget([
            'model' => $model,
            'property' => 'currencySymbolPlace',
            'form' => $form
        ]); ?>
    </div>
</div>


<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'currencySmallestUnit')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-6">
        <?= Toggler::widget([
            'model' => $model,
            'property' => 'currencyRemoveZeros',
            'form' => $form
        ]); ?>
    </div>
</div>