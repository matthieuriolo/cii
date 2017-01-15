<?php
use cii\helpers\Url;
use cii\widgets\Toggler;
use cii\widgets\EditView;

echo EditView::widget([
	'model' => $model,
     'form' => $form,
     'attributes' => [
	    'slug',
		'type:routetypes',
		'enabled:boolean',
		'title',
		'parent_id:route',
		[
			'attribute' => 'language_id',
			'format' => 'language',
			'visible' => Yii::$app->cii->package->setting('cii', 'multilanguage'),
		]
	]
]);
?>

<?php /*
<div class="row">
	<div class="col-md-6">
		<?php echo $form->field($model, 'slug'); ?>
	</div>

	<div class="col-md-6">
		<?php
		echo $form->field($model, 'type', [
			'inputOptions' => [
				'data-controller' => 'lazy-create',
				'data-url' => Url::toRoute(Yii::$app->seo->relativeAdminRoute('route/lazy'), true),
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

	<div class="col-md-6">
		<?php echo $form->field($model, 'title')->textInput(['maxlength' => true]); ?>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<?php
		$field = Yii::$app->cii->createFieldObject('route', ['attribute' => 'parent_id']);
		echo $field->getEditable($model, $form);
		?>
	</div>

	<?php if(Yii::$app->cii->package->setting('cii', 'multilanguage')) { ?>
	<div class="col-md-6">
		<?php echo $form->field($model, 'language_id')->dropDownList($languages); ?>
	</div>
	<?php } ?>
</div>
*/ ?>