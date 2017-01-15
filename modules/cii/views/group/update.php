<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::p('cii', 'Update {modelClass} - ', [
    'modelClass' => Yii::p('cii', 'Group'),
]) . $model->name;
$this->params['breadcrumbs'][] = [
	'label' => Yii::p('cii', 'Groups'),
	'url' => [Yii::$app->seo->relativeAdminRoute('group/index')]
];
$this->params['breadcrumbs'][] = [
	'label' => $model->name,
	'url' => [Yii::$app->seo->relativeAdminRoute('group/view'), 'id' => $model->id]
];
$this->params['breadcrumbs'][] = Yii::p('cii', 'Update');
?>
<div class="group-update">
	<?php $form = ActiveForm::begin(); ?>

	<div class="form-group pull-right">
		<?php echo Html::a(
	        Yii::p('cii', 'Cancel'),
	        [Yii::$app->seo->relativeAdminRoute('group/index')],
	        ['class' => 'btn btn-warning']
	    ); ?>

        <?= Html::submitButton(Yii::p('cii', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
		'model' => $model,
		'form' => $form
	]); ?>
    <?php ActiveForm::end(); ?>
</div>
