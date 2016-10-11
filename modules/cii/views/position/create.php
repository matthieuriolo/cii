<?php

use yii\helpers\Html;
use cii\widgets\EditView;
use yii\bootstrap\Tabs;
use yii\widgets\ActiveForm;



$this->title = Yii::p('cii', 'Add Position');
$this->params['breadcrumbs'][] = [
    'label' => Yii::p('cii', 'Positions'),
    'url' => [\Yii::$app->seo->relativeAdminRoute('modules/cii/position/index')]
];
$this->params['breadcrumbs'][] = $this->title;
?>


<?php $form = ActiveForm::begin(); ?>
<?= Html::submitButton(Yii::p('cii', 'Create'), ['class' => 'btn btn-success pull-right']) ?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', [
    'model' => $model,
    'form' => $form,
]); ?>


<?php ActiveForm::end(); ?>


