<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::p('cii', 'Send mail to {user}', ['user' => $model->username]);
$this->params['breadcrumbs'][] = [
    'label' => Yii::p('cii', 'Users'),
    'url' => [Yii::$app->seo->relativeAdminRoute('user/index')]
];
$this->params['breadcrumbs'][] = [
    'label' => $model->username,
    'url' => [Yii::$app->seo->relativeAdminRoute('user/view'), 'id' => $model->id]
];
$this->params['breadcrumbs'][] = Yii::p('cii', 'Update');
?>
<div class="user-update">

    <?php $form = ActiveForm::begin(); ?>
    
	<div class="form-group pull-right">
		<?php echo Html::a(
	        Yii::p('cii', 'Cancel'),
	        [Yii::$app->seo->relativeAdminRoute('user/view'), 'id' => $model->id],
	        ['class' => 'btn btn-warning']
	    ); ?>

        <?= Html::submitButton(Yii::p('cii', 'Send'), ['class' => 'btn btn-primary']) ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'subject')->textInput(['maxlength' => true]); ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'cc')->textInput(['maxlength' => true]); ?>
        </div>
    </div>

    <hr>

    <?= $form->field($model, 'content')->textarea(['rows' => 20, 'data-controller' => 'wysihtml5']); ?>

    <?php ActiveForm::end(); ?>
</div>
