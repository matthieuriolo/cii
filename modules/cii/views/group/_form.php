<?php

use yii\helpers\Html;
use cii\widgets\Toggler;

?>

<div class="row">
    <div class="col-md-6">
	    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
	</div>

	<div class="col-md-6">
        <?= Toggler::widget([
            'model' => $model,
            'property' => 'enabled',
            'form' => $form
        ]); ?>
    </div>
</div>
