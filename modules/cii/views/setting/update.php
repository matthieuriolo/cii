<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cii\widgets\Toggler;


$this->title = Yii::p('cii', 'Update Setting');
$this->params['breadcrumbs'][] = ['label' => Yii::p('cii', 'Settings'), 'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/setting/index')]];
$this->params['breadcrumbs'][] = $this->title;
?>


<?php $form = ActiveForm::begin(); ?>

<div class="form-group pull-right">
	<?php echo Html::a(
        Yii::p('cii', 'Cancel'),
        [Yii::$app->seo->relativeAdminRoute('modules/cii/setting/index')],
        ['class' => 'btn btn-warning']
    ); ?>

    <?= Html::submitButton(Yii::p('cii', 'Update'), ['class' => 'btn btn-primary']) ?>
</div>

<h1><?= Html::encode($this->title) ?></h1>


<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <?= Html::activeLabel($model, 'extension_id'); ?>
            <p class="form-control-static"><?= 
                Html::a($model->extension->name, [
                    Yii::$app->seo->relativeAdminRoute('modules/cii/extension'),
                    'id' => $model->extension->id
                ]);
            ?></p>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <?= Html::activeLabel($model, 'name'); ?>
            <p class="form-control-static"><?= $model->label; ?></p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <?= Html::activeLabel($model, 'type'); ?>
            <p class="form-control-static"><?= $model->getTranslatedType(); ?></p>
        </div>
    </div>

    <div class="col-md-6">
        <?= $model->getField()->getEditable($model, $form); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
