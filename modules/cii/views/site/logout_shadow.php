<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<h3><?= Yii::t('app', 'Logout'); ?></h3>
<?php
$form = ActiveForm::begin();

echo $form->field($model, 'authKey')->hiddenInput()->label(false);
?>
<p class="text-center">
<?= Html::submitButton(Yii::t('app', 'Logout'), ['class' => 'btn btn-primary']); ?>
</p>

<?php ActiveForm::end();