<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cii\widgets\Toggler;

$this->title = Yii::t('app', 'Update Profile');

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
	    	<?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
	    </div>

	    <div class="col-md-6">
	    	<div class="form-group">
		    	<?= Html::activeLabel($model, 'password', ['class' => 'no-padding']); ?>

		    	<div class="form-control-static">
			    	<?= Html::a(
		                Yii::t('yii', 'Change password'),
		                ['password'],
		                ['class' => 'btn btn-warning']
		            ); ?>
		        </div>
	        </div>
	    </div>
	</div>

	<div class="row">
	    <div class="col-md-6">
	    	<div class="form-group">
		    	<?= Html::activeLabel($model, 'email', ['class' => 'no-padding']); ?>

		    	<div class="form-control-static">
			    	<?= Html::a(
		                Yii::t('yii', 'Change email'),
		                ['email'],
		                ['class' => 'btn btn-warning']
		            ); ?>
		        </div>
	        </div>
	    </div>

	    <div class="col-md-6">
	    	<div class="form-group">
		    	<?= Html::activeLabel($model, 'password', ['class' => 'no-padding']); ?>

		    	<div class="form-control-static">
			    	<?= Html::a(
		                Yii::t('yii', 'Delete account'),
		                ['delete'],
		                ['class' => 'btn btn-danger']
		            ); ?>
		        </div>
	        </div>
	    </div>
	</div>

<?php ActiveForm::end(); ?>
