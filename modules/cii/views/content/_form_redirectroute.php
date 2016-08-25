<?php

use yii\helpers\Html;

?>

<div class="row">
	<div class="col-md-6">
		<?= $form->field($model, 'redirect_id')->dropDownList($routes); ?>
	</div>

	<div class="col-md-6">
		<?= $form->field($model, 'type')->dropDownList($model->getTypes()); ?>
	</div>
</div>


<div class="row">
	<div class="col-md-6">
		<?= $form->field($model, 'url')->textInput(['maxlength' => true]); ?>
	</div>
</div>