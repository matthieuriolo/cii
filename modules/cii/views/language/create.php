<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::p('cii', 'Create Language');
$this->params['breadcrumbs'][] = [
	'label' => Yii::p('cii', 'Languages'),
	'url' => [\Yii::$app->seo->relativeRoute('app\modules\cii\routes\BackendModules', 'language/index')]
];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(); ?>
	<div class="form-group pull-right">
        <?php 
		echo Html::a(
            Yii::p('cii', 'Cancel'),
            [\Yii::$app->seo->relativeAdminRoute('modules/cii/language/index')],
            ['class' => 'btn btn-warning']
        ),
        '&nbsp;',
        Html::submitButton(Yii::p('cii', 'Create'), ['class' => 'btn btn-success']);
        ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'form' => $form
    ]) ?>


<?php ActiveForm::end(); ?>
