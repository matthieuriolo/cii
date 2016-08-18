<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Send mail to {user}', ['user' => $model->username]);
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Users'),
    'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/user/index')]
];
$this->params['breadcrumbs'][] = [
    'label' => $model->username,
    'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/user/view'), 'id' => $model->id]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-update">

    <?php $form = ActiveForm::begin(); ?>
    
	<div class="form-group pull-right">
		<?php echo Html::a(
	        Yii::t('yii', 'Cancel'),
	        [Yii::$app->seo->relativeAdminRoute('modules/cii/user/view'), 'id' => $model->id],
	        ['class' => 'btn btn-warning']
	    ); ?>

        <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary']) ?>
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
