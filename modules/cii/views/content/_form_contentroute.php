<?php

use yii\helpers\Html;

?>

<div class="row">
	<div class="col-md-6">
		<?= $form->field($model, 'content_id')->dropDownList($contents); ?>
	</div>

	<div class="col-md-6">
		<?= $form->field($model, 'keys')->textInput(['maxlength' => true]); ?>
	</div>
</div>


<div class="row">
	<div class="col-md-6">
		<?= $form->field($model, 'description')->textInput(['maxlength' => true]); ?>
	</div>

	<div class="col-md-6">
		<?= $form->field($model, 'image')->textInput(['maxlength' => true]); ?>
	</div>
</div>


<div class="row">
	<div class="col-md-6">
		<?= $form->field($model, 'type')->dropDownList($model->getTypesForDropdown()); ?>
	</div>

	<div class="col-md-6">
		<?= $form->field($model, 'robots')->dropDownList($model->getRobotTypesForDropdown()); ?>
	</div>
</div>