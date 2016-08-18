<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use cii\widgets\Toggler;


$this->title = Yii::t('app', 'Register');
$this->params['breadcrumbs'][] = $this->title;
?>
<main>
	<h1><?php echo Yii::t('app', 'Register'); ?></h1>

	<p class="lead"><?php echo Yii::t('app', 'Please enter your credentials'); ?></p>

	<?php $form = ActiveForm::begin(); ?>

		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
			</div>

			<div class="col-md-6">
				<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'password_field')->passwordInput(['data-controller' => 'strengthcheck']) ?>
			</div>

			<div class="col-md-6">
				<?= $form->field($model, 'password_repeat')->passwordInput(['data-controller' => 'strengthcheck']) ?>
			</div>
		</div>

		<div class="row">
			<?php if(Captcha::checkRequirements()): ?>
				<div class="col-md-6">
					<?php 
					echo $form->field($model, 'captcha')->widget(Captcha::classname(), [
						'captchaAction' => '//' . Yii::$app->seo->relativeRoute('app\modules\cii\routes\Content', 'captcha'),
						'template' => '<div class="row"><div class="col-md-3" role="button" title="Reload image">{image}</div><div class="col-md-9">{input}</div></div>',
					]); ?>
				</div>
			<?php endif; ?>
		</div>
		
		<hr>

		<?php if($content->login_id) { ?>
			<p class="text-center"><?php
				echo Yii::t('app', 'You already have an {link}', ['link' => Html::a(Yii::t('app', 'account'), ['//' . $content->login->getBreadcrumbs()])]);
			?></p>
		<?php } ?>

		<?php if($content->forgot_id) { ?>
			<p class="text-center"><?php
				echo Yii::t('app', 'Did you {link} your password?', ['link' => Html::a(Yii::t('app', 'forgot'), ['//' . $content->forgot->getBreadcrumbs()])]);
			?></p>
		<?php } ?>

		<div class="form-group buttons text-center">
			<?php echo Html::submitButton(Yii::t('app', 'Register'), array('class' => 'btn btn-primary')); ?>
		</div>
	 <?php ActiveForm::end(); ?>
</main>