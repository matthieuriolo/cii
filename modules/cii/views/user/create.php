<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Create User');
$this->params['breadcrumbs'][] = [
	'label' => Yii::t('app', 'Users'),
	'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/user/index')]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">
	<?php $form = ActiveForm::begin(); ?>

	<div class="form-group pull-right">
		<?php echo Html::a(
	        Yii::t('yii', 'Cancel'),
	        [Yii::$app->seo->relativeAdminRoute('modules/cii/user/index')],
	        ['class' => 'btn btn-warning']
	    ); ?>

        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'form' => $form
    ]) ?>

    <?php ActiveForm::end(); ?>
</div>
