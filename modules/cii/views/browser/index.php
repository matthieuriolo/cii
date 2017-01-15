<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use cii\widgets\Toggler;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Modal;

$this->title = Yii::p('cii', 'File browser');
$this->params['breadcrumbs'][] = $this->title;
?>
<main>
	<h1><?php echo Html::encode($this->title); ?></h1>
	<div class="file-browser">
		<?php

		$links = [
			[
	        	'label' => Yii::p('cii', 'Main'),
	        	'url' => [Yii::$app->seo->relativeAdminRoute('browser/index')]
	        ]
		];

		if($path) {
			$tks = explode('/', $path);
			for($i = 0; $i < count($tks); $i++) {
				$subpath = [];
				for($a = 0; $a <= $i; $a++) {
					$subpath[] = $tks[$a];
				}

				$subpath = implode('/', $subpath);
				$links[] = [
					'label' => $tks[$i],
					'url' => [Yii::$app->seo->relativeAdminRoute('browser/index'), 'path' => $subpath]
				];
			}
		}

		echo Breadcrumbs::widget([
	        'links' => $links,
	        'homeLink' => false
	    ]);
		
		?>
	
		<?php $form = ActiveForm::begin([
			'action' => [Yii::$app->seo->relativeAdminRoute('browser/upload'), 'path' => $path],
			'method' => 'post',
			'options' => ['enctype' => 'multipart/form-data']
		]); ?>
		<?= $form->field($uploadModel, 'files[]')->fileInput(['data-controller' => 'submit-onchange', 'multiple' => true])->label(false); ?>
		<?php ActiveForm::end();


		$modal = Modal::begin([
		    'header' => '<h2>' . Yii::p('cii', 'Rename item') . '</h2>',
		]);

		$form = ActiveForm::begin([
			'action' => [Yii::$app->seo->relativeAdminRoute('browser/rename'), 'path' => $path],
			'method' => 'post',
		]); ?>
		<?php /*$form->field($renameModel, 'original')->hiddenInput()->label(false);*/ ?>
		<?= Html::activeHiddenInput($renameModel, 'original'); ?>
		
		<?= $form->field($renameModel, 'name')->textInput(); ?>
		<div class="form-group text-right">
			<?= Html::a(Yii::p('cii', 'Cancel'), '#', ['onclick' => '$("#'.$modal->options['id'].'").modal("toggle")', 'class' => 'btn btn-warning']) ?>
			<?= Html::submitButton(Yii::p('cii', 'Update'), ['class' => 'btn btn-primary']) ?>
		</div>
		<?php ActiveForm::end();

		Modal::end();
		 
		 $this->registerJs(
			'$("#'. $modal->options['id'] . '").on("shown.bs.modal", function(evt) {
				var originalName = $(evt.relatedTarget).data("original-name");

		        $("#'. $modal->options['id'] . '").find("input[name=\"RenameFileForm[name]\"]").val(originalName)
		        $("#'. $modal->options['id'] . '").find("input[name=\"RenameFileForm[original]\"]").val(originalName)
		    })');
		?>
		<hr>

		<div class="file-browser-elements">
			<?php foreach($files as $file) {
				echo $file->getDisplayContent($modal->options['id']);
			} ?>
		</div>
	</div>
</main>