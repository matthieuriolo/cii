<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::p('cii', 'Create User');
$this->params['breadcrumbs'][] = [
	'label' => Yii::p('cii', 'Users'),
	'url' => [Yii::$app->seo->relativeAdminRoute('user/index')]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">
	<?php $form = ActiveForm::begin(); ?>

	<div class="form-group pull-right">
		<?php echo Html::a(
	        Yii::p('cii', 'Cancel'),
	        [Yii::$app->seo->relativeAdminRoute('user/index')],
	        ['class' => 'btn btn-warning']
	    ); ?>

        <?= Html::submitButton(Yii::p('cii', 'Create'), ['class' => 'btn btn-success']) ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'form' => $form
    ]) ?>

    <?php ActiveForm::end(); ?>
</div>
