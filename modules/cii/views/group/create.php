<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\cii\models\Group */

$this->title = Yii::t('app', 'Create Group');
$this->params['breadcrumbs'][] = [
	'label' => Yii::t('app', 'Groups'),
	'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/group/index')]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-create">
	<?php $form = ActiveForm::begin(); ?>
	
	<div class="form-group pull-right">
		<?php echo Html::a(
	        Yii::t('yii', 'Cancel'),
	        [Yii::$app->seo->relativeAdminRoute('modules/cii/group/index')],
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
