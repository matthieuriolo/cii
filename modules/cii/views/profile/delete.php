<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cii\widgets\Toggler;
use yii\captcha\Captcha;

$this->title = Yii::t('app', 'Delete account');
$this->params['breadcrumbs'][] = [
	'label' => Yii::t('app', 'Profile'),
	'url' => ['//'. Yii::$app->seo->getBaseRoute()]
];

$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(); ?>
	<div class="form-group pull-right">
        <?php echo Html::a(
                Yii::t('yii', 'Cancel'),
                [''],
                ['class' => 'btn btn-warning']
            ),
            '&nbsp;',
            Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']);
       	?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    
	<div class="row">
	    <div class="col-md-6">
	    	<?= $form->field($model, 'password_field')->passwordInput() ?>
	    </div>

	    <?php if(Captcha::checkRequirements()): ?>
			<div class="col-md-6">
				<?php 
				echo $form->field($model, 'captcha')->widget(Captcha::classname(), [
					'captchaAction' => '//' . Yii::$app->seo->relativeRoute('app\modules\cii\routes\Profile', 'captcha'),
					'template' => '<div class="row"><div class="col-md-3" role="button" title="Reload image">{image}</div><div class="col-md-9">{input}</div></div>',
				]); ?>
			</div>
		<?php endif; ?>
	</div>

<?php ActiveForm::end(); ?>
