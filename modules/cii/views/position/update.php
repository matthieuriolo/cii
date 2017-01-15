<?php

use yii\helpers\Html;
use cii\widgets\EditView;
use yii\bootstrap\Tabs;
use yii\widgets\ActiveForm;



$this->title = Yii::p('cii', 'Update Position');
$this->params['breadcrumbs'][] = [
    'label' => Yii::p('cii', 'Positions'),
    'url' => [\Yii::$app->seo->relativeAdminRoute('position/index')]
];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(); ?>
<p class="pull-right">
    <?php
        echo Html::a(
            Yii::p('cii', 'Cancel'),
            [\Yii::$app->seo->relativeAdminRoute('position/index')],
            ['class' => 'btn btn-warning']
        ),
        '&nbsp;',
        Html::submitButton(Yii::p('cii', 'Update'), ['class' => 'btn btn-primary'])
    ?>
</p>


<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', [
    'model' => $model,
    'form' => $form
]); ?>
<?php ActiveForm::end(); ?>
