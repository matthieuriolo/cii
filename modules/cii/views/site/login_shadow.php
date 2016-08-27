<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use cii\widgets\Toggler;

?>
<h3><?= Yii::p('cii', 'Login') ?></h3>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'password')->passwordInput([
	'data-controller' => 'strengthcheck',
	'data-position' => $position == 'left' ? 'right' : 'left'
]) ?>

	<?php /*if(Captcha::checkRequirements()) {
		echo $form->field($model, 'captcha')->widget(Captcha::classname(), [
				'captchaAction' => Yii::$app->seo->relativeRoute('app\modules\cii\routes\Content', 'captcha'),
				'template' => '<div class="row"><div class="col-md-3" role="button" title="Reload image">{image}</div><div class="col-md-9">{input}</div></div>',
			]);
	}*/ ?>

	<?php /* echo Toggler::widget([
		'form' => $form,
		'model' => $model,
		'property' => 'rememberMe'
	]);*/ ?>

	<hr>

	<?php if($content->register_id || $content->forgot_id) { ?>
		<p class="text-center"><?php
			if($content->register_id) {
				echo Html::a(Yii::p('cii', 'Register'), ['//' . $content->register->getBreadcrumbs()]);
			}

			echo '&nbsp;';

			if($content->forgot_id) {
				echo Html::a(Yii::p('cii', 'Forgot'), ['//' . $content->forgot->getBreadcrumbs()]);
			}
		?></p>
	<?php } ?>

	<div class="form-group buttons text-center">
		<?php echo Html::submitButton(Yii::p('cii', 'Login'), array('class' => 'btn btn-primary')); ?>
	</div>
 <?php ActiveForm::end(); ?>
