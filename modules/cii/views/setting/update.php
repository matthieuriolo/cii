<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cii\widgets\Toggler;


$this->title = Yii::t('app', 'Update Setting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/setting/index')]];
$this->params['breadcrumbs'][] = $this->title;
?>


<?php $form = ActiveForm::begin(); ?>

<div class="form-group pull-right">
	<?php echo Html::a(
        Yii::t('yii', 'Cancel'),
        [Yii::$app->seo->relativeAdminRoute('modules/cii/setting/index')],
        ['class' => 'btn btn-warning']
    ); ?>

    <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
</div>

<h1><?= Html::encode($this->title) ?></h1>


<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <?= Html::activeLabel($model, 'extension_id'); ?>
            <p class="form-control-static"><?= $model->extension->name; ?></p>
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
            <p class="form-control-static"><?= $model->type; ?></p>
        </div>
    </div>

    <div class="col-md-6">
        <?= $model->render($this, $form); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
