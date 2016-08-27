<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<h3><?= Yii::p('cii', 'Logout'); ?></h3>
<?php
$form = ActiveForm::begin();

echo $form->field($model, 'authKey')->hiddenInput()->label(false);
?>
<p class="text-center">
<?= Html::submitButton(Yii::p('cii', 'Logout'), ['class' => 'btn btn-primary']); ?>
</p>

<?php ActiveForm::end();