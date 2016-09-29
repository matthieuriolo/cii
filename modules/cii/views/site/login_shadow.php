<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use cii\widgets\Toggler;

use cii\widgets\EditView;

?>
<h3><?= Yii::p('cii', 'Login') ?></h3>

<?php $form = ActiveForm::begin();

	echo EditView::widget([
	    'model' => $model,
	    'form' => $form,
	    'columns' => $content->content->columns_count,
	    'attributes' => [
	        'email:email',
	        [
	        	'attribute' => 'password',
	        	'format' => 'password',
	        	'popoverPosition' => $position == 'left' ? 'right' : 'left'
	        ],

	        [
	        	'attribute' => 'captcha',
	        	'format' => 'captcha',
	        	'visible' => $model->captchaRoute
	        ],

	        [
	        	'attribute' => 'rememberMe',
	        	'format' => 'boolean',
	        	'visible' => $content->remember_visible,
	        ]
	    ],
	]);
	?>
	
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
