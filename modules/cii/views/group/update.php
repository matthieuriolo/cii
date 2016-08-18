<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Update {modelClass} - ', [
    'modelClass' => 'Group',
]) . $model->name;
$this->params['breadcrumbs'][] = [
	'label' => Yii::t('app', 'Groups'),
	'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/group/index')]
];
$this->params['breadcrumbs'][] = [
	'label' => $model->name,
	'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/group/view'), 'id' => $model->id]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="group-update">
	<?php $form = ActiveForm::begin(); ?>

	<div class="form-group pull-right">
		<?php echo Html::a(
	        Yii::t('yii', 'Cancel'),
	        [Yii::$app->seo->relativeAdminRoute('modules/cii/group/index')],
	        ['class' => 'btn btn-warning']
	    ); ?>

        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
		'model' => $model,
		'form' => $form
	]); ?>
    <?php ActiveForm::end(); ?>
</div>
