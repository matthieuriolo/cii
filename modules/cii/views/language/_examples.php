<?php

use yii\helpers\Html;
use cii\widgets\DetailView;

use yii\widgets\ActiveForm;

$form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'numeric')->textInput(); ?>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">&nbsp;</label>
            <div class="form-control-static no-padding">
                <?= Html::submitButton(Yii::p('cii', 'Submit'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'date',
        'time',
        'datetime',
        'integer',
        'float',
        'currency',
    ],
]) ?>