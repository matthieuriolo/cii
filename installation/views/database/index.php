<h3>Introducing</h3>
<p>
You need to have a working database in order to run Cii. Please configure your database settings
</p>

<?php


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Database';
?>

<h3>Configuration</h3>
<?php $form = ActiveForm::begin(['id' => 'database-form']); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'mode')->dropDownList($model->namedModes()) ?>
            
            <?= $form->field($model, 'host') ?>
            
            <?= $form->field($model, 'dbname') ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'username') ?>

            <?= $form->field($model, 'password')->passwordInput(); ?>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <a href="<?php $url; ?>index.php?r=paths" class="pull-left btn btn-default">Previous</a>
            
            <?= Html::submitButton('Next', [
                'class' => 'btn btn-primary pull-right' . (!isset($_POST['test-button']) || $model->hasErrors() ? ' disabled': ''),
                'name' => 'database-button'
            ]) ?>
            
            <?= Html::submitButton('Test Connection', [
                'class' => 'btn btn-default pull-right',
                'name' => 'test-button',
                'style' => 'margin-right: 10px;'
            ]) ?>
        </div>
    </div>
    
<?php ActiveForm::end(); ?>
