<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use cii\widgets\Toggler;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Modal;

$baseModule = 'modules/cii/popup/browser/';
$types = Yii::$app->request->get('types');
$parentId = Yii::$app->request->get('iframe');

$this->title = Yii::p('cii', 'File browser');
$this->params['breadcrumbs'][] = $this->title;
?>
<main>
	<div class="file-browser">
		<?php

		$links = [
			[
	        	'label' => Yii::p('cii', 'Main Directory'),
	        	'url' => [Yii::$app->seo->relativeAdminRoute($baseModule . 'index')]
	        ]
		];

		if($types) {
			$links[0]['url']['types'] = $types;
		}

		if($parentId) {
			$links[0]['url']['iframe'] = $parentId;
		}

		if($path) {
			$tks = explode('/', $path);
			for($i = 0; $i < count($tks); $i++) {
				$subpath = [];
				for($a = 0; $a <= $i; $a++) {
					$subpath[] = $tks[$a];
				}

				$subpath = implode('/', $subpath);
				$link = [
					'label' => $tks[$i],
					'url' => [Yii::$app->seo->relativeAdminRoute($baseModule . 'index'), 'path' => $subpath]
				];

				if($types) {
					$link['url']['types'] = $types;
				}

				if($parentId) {
					$link['url']['iframe'] = $parentId;
				}

				$links[] = $link;
			}
		}

		echo Breadcrumbs::widget([
	        'links' => $links,
	        'homeLink' => false
	    ]);
		
		?>
	
		<?php $form = ActiveForm::begin([
			'action' => [Yii::$app->seo->relativeAdminRoute($baseModule . 'upload'), 'path' => $path],
			'method' => 'post',
			'options' => ['enctype' => 'multipart/form-data']
		]); ?>
		<?= $form->field($uploadModel, 'files[]')->fileInput(['data-controller' => 'submit-onchange', 'multiple' => true])->label(false); ?>
		<?php ActiveForm::end();


		$modal = Modal::begin([
		    'header' => '<h2>' . Yii::p('cii', 'Rename item') . '</h2>',
		]);

		$form = ActiveForm::begin([
			'action' => [Yii::$app->seo->relativeAdminRoute($baseModule . 'rename'), 'path' => $path],
			'method' => 'post',
		]); ?>
		
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
		    });

			$(".file-browser").click(function(evt) {
				$(".file-browser .file-browser-elements .element").removeClass("active");
				$(window.parent.document).find("#' . $parentId . '_field").val("");
				$(window.parent.document).find("#' . $parentId . '_submit").addClass("disabled");
			});

			$(".file-browser .file-browser-elements .element").click(function(evt) {
				if(!$(evt.target).is("i") || $(evt.target).parent().is(".preview-container")) {
					if($(evt.currentTarget).hasClass("disabled")) {
						return;
					}

					evt.stopPropagation();
					$(".file-browser .file-browser-elements .element").removeClass("active");
					$(evt.currentTarget).addClass("active");

					var path = "' . ($path ? $path . '/' : '') . '" + $(evt.currentTarget).find(".name").text();
					$(window.parent.document).find("#' . $parentId . '_field").val(path);
					$(window.parent.document).find("#' . $parentId . '_submit").removeClass("disabled");
				}
			}
		);'); ?>
		<hr>

		<div class="file-browser-elements">
			<?php
			if($types) {
				$types = explode(',', $types);
			}

			foreach($files as $file) {
				echo $file->getDisplayContent(
					$modal->options['id'],
					$types,
					$parentId
				);
			} ?>
		</div>
	</div>
</main>