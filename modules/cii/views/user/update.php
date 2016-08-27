<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::p('cii', 'Update {modelClass} - ', [
    'modelClass' => Yii::p('cii', 'User'),
]) . $model->username;
$this->params['breadcrumbs'][] = [
    'label' => Yii::p('cii', 'Users'),
    'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/user/index')]
];
$this->params['breadcrumbs'][] = [
    'label' => $model->username,
    'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/user/view'), 'id' => $model->id]
];
$this->params['breadcrumbs'][] = Yii::p('cii', 'Update');
?>

<div class="user-update">
    <?php $form = ActiveForm::begin(); ?>
    
	<div class="form-group pull-right">
		<?php echo Html::a(
	        Yii::p('cii', 'Cancel'),
	        [Yii::$app->seo->relativeAdminRoute('modules/cii/user/index')],
	        ['class' => 'btn btn-warning']
	    ); ?>

        <?= Html::submitButton(Yii::p('cii', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'form' => $form
    ]) ?>

    <?php ActiveForm::end(); ?>
</div>
