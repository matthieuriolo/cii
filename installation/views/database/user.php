<?php


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Superadmin';
?>

<h3>Superadmin</h3>
<p class="lead">The superadmin has everyright to change the system! </p>

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
            <?= $form->field($model, 'password_field')->passwordInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <a href="<?php $url; ?>index.php?r=database/init" class="pull-left btn btn-default">Previous</a>
            
            <?= Html::submitButton('Save', [
                'class' => 'btn btn-primary pull-right',
            ]) ?>
        </div>
    </div>
    
<?php ActiveForm::end(); ?>
