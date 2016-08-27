<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cii\widgets\Toggler;

$this->title = Yii::p('cii', 'Update Profile');

$this->params['breadcrumbs'][] = [
	'label' => Yii::p('cii', 'Profile'),
	'url' => ['//'. Yii::$app->seo->getBaseRoute()]
];

$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(); ?>
	<div class="form-group pull-right">
        <?php echo Html::a(
                Yii::p('cii', 'Cancel'),
                [''],
                ['class' => 'btn btn-warning']
            ),
            '&nbsp;',
            Html::submitButton(Yii::p('cii', 'Update'), ['class' => 'btn btn-primary']);
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
		                Yii::p('cii', 'Change password'),
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
		                Yii::p('cii', 'Change email'),
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
		                Yii::p('cii', 'Delete account'),
		                ['delete'],
		                ['class' => 'btn btn-danger']
		            ); ?>
		        </div>
	        </div>
	    </div>
	</div>

<?php ActiveForm::end(); ?>
