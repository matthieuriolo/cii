<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use cii\widgets\Toggler;

$this->title = Yii::p('cii', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<main>
	<h1><?php echo Yii::p('cii', 'Login'); ?></h1>

	<p class="lead"><?php echo Yii::p('cii', 'Please enter your credentials'); ?></p>

	<?php $form = ActiveForm::begin(); ?>
		
		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
			</div>

			<div class="col-md-6">
				<?= $form->field($model, 'password')->passwordInput(['data-controller' => 'strengthcheck']) ?>
			</div>
		</div>

		<div class="row">
			<?php if(Captcha::checkRequirements()): ?>
				<div class="col-md-6">
					<?php 
					echo $form->field($model, 'captcha')->widget(Captcha::classname(), [
						'captchaAction' => Yii::$app->seo->relativeRoute('app\modules\cii\routes\Content', 'captcha'),
						'template' => '<div class="row"><div class="col-md-3" role="button" title="Reload image">{image}</div><div class="col-md-9">{input}</div></div>',
					]); ?>
				</div>
			<?php endif; ?>

			<div class="col-md-6">
				<?php echo Toggler::widget([
					'form' => $form,
					'model' => $model,
					'property' => 'rememberMe'
				]); ?>
			</div>
		</div>
		
		<hr>
		<?php if($content->register_id) { ?>
			<p class="text-center"><?php
				echo Yii::p('cii', 'Create a new {link}', ['link' => Html::a(Yii::p('cii', 'acount'), ['//' . $content->register->getBreadcrumbs()])]);
			?></p>
		<?php } ?>

		<?php if($content->forgot_id) { ?>
			<p class="text-center"><?php
				echo Yii::p('cii', 'Did you {link} your password?', ['link' => Html::a(Yii::p('cii', 'forgot'), ['//' . $content->forgot->getBreadcrumbs()])]);
			?></p>
		<?php } ?>

		<div class="form-group buttons text-center">
			<?php echo Html::submitButton(Yii::p('cii', 'Login'), array('class' => 'btn btn-primary')); ?>
		</div>
	 <?php ActiveForm::end(); ?>
</main>