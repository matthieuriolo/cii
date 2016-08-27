<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::p('cii', 'Update {modelClass} - ', [
    'modelClass' => Yii::p('cii', 'Language'),
]) . $model->name;
$this->params['breadcrumbs'][] = [
    'label' => Yii::p('cii', 'Languages'),
    'url' => [\Yii::$app->seo->relativeRoute('app\modules\cii\routes\BackendModules', 'language/index')]
];

$this->params['breadcrumbs'][] = [
	'label' => $model->name,
	'url' => [\Yii::$app->seo->relativeRoute('app\modules\cii\routes\BackendModules', 'language/view'), ['id' => $model->id]]
];
$this->params['breadcrumbs'][] = Yii::p('cii', 'Update');
?>

<?php $form = ActiveForm::begin(); ?>
	<div class="form-group pull-right">
        <?php echo Html::a(
                Yii::p('cii', 'Cancel'),
                [\Yii::$app->seo->relativeAdminRoute('modules/cii/language/view'), ['id' => $model->id]],
                ['class' => 'btn btn-warning']
            ),
            '&nbsp;',
            Html::submitButton(Yii::p('cii', 'Update'), ['class' => 'btn btn-primary']);
       	?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
         'form' => $form
    ]) ?>
<?php ActiveForm::end(); ?>
