<?php

use yii\helpers\Html;
use cii\widgets\Toggler;
use cii\widgets\EditView;
?>
<?php echo EditView::widget([
     'model' => $model,
     'form' => $form,
     'attributes' => [
            'name:text',
            'code:text',
            'shortcode:text',
            'enabled:boolean',
        ]
    ]);
?>
<?php /*
<div class="row">
    <div class="col-md-6">
    	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-6">
		<?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
	</div>
</div>

<div class="row">
    <div class="col-md-6">
	    <?= $form->field($model, 'shortcode')->textInput(['maxlength' => true]) ?>
	</div>

	<div class="col-md-6">
		<?= Toggler::widget([
            'model' => $model,
            'property' => 'enabled',
            'form' => $form
        ]); ?>
    </div>
</div>
*/ ?>
<hr>

<?php echo EditView::widget([
     'columns' => isset($columns) && $columns > 0 ? $columns : null,
     'model' => $model,
     'form' => $form,
     'attributes' => [
            'time:text',
            'date:text',
            'datetime:text',
            'thousandSeparator:text',
            'decimalSeparator:text',
            'decimals:integer',
            'removeZeros:boolean'
        ]
    ]);
?>
<?php /*
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'time')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'date')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'datetime')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'thousandSeparator')->textInput(['maxlength' => true]) ?>
    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'decimalSeparator')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'decimals')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-6">
        <?= Toggler::widget([
            'model' => $model,
            'property' => 'removeZeros',
            'form' => $form
        ]); ?>
    </div>
</div>
*/ ?>
<hr>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'currencySymbol')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-6">
        <?= Toggler::widget([
            'model' => $model,
            'property' => 'currencySymbolPlace',
            'form' => $form
        ]); ?>
    </div>
</div>


<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'currencySmallestUnit')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-6">
        <?= Toggler::widget([
            'model' => $model,
            'property' => 'currencyRemoveZeros',
            'form' => $form
        ]); ?>
    </div>
</div>