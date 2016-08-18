<?php

use yii\helpers\Html;
use cii\helpers\Url;
use cii\widgets\Toggler;
?>

<div class="row">
	<div class="col-md-6">
		<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
	</div>
	
	<div class="col-md-6">
		<?php echo $form->field($model, 'type', [
			'inputOptions' => [
				'data-controller' => 'lazy-create',
				'data-url' => Url::toRoute(Yii::$app->seo->relativeAdminRoute('modules/cii/content/lazy'), true),
				'data-tabs' => 1,
				'class' => 'form-control'
			]
		])->dropDownList($types); ?>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<?= Toggler::widget([
            'model' => $model,
            'property' => 'enabled',
            'form' => $form
        ]); ?>
	</div>
</div>