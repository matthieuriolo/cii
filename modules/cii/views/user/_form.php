<?php

use yii\helpers\Html;
use cii\widgets\Toggler;
?>


<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<?php if($model->isNewRecord) { ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'password_field')->passwordInput(['data-controller' => 'strengthcheck']); ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'password_repeat')->passwordInput(['data-controller' => 'strengthcheck']); ?>
        </div>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-6">
        <?= Toggler::widget([
            'model' => $model,
            'property' => 'enabled',
            'form' => $form
        ]); ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'layout_id')->dropDownList(Yii::$app->cii->layout->getLayoutsForDropdown()) ?>
    </div>
</div>

<?php if(Yii::$app->cii->package->setting('cii', 'multilanguage')) { ?>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'language_id')->dropDownList(Yii::$app->cii->language->getLanguagesForDropdown()) ?>
    </div>
</div>
<?php } ?>