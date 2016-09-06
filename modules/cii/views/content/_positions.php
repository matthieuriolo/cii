<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;

$form = ActiveForm::begin();
$multilanguage = Yii::$app->cii->package->setting('cii', 'multilanguage');

if($multilanguage) {
    $col = 3;
}else {
    $col = 4;
}

?>

<div class="row">
    <div class="col-md-<?= $col ?>">
        <?= $form->field($visibleModel, 'position')->dropDownList($positions); ?>
    </div>
    
    <div class="col-md-<?= $col ?>">
        <?= $form->field($visibleModel, 'route_id')->dropDownList($routes); ?>
    </div>
    
    <?php if($multilanguage) { ?>
    <div class="col-md-<?= $col ?>">
        <?= $form->field($visibleModel, 'language_id')->dropDownList($languages); ?>
    </div>
    <?php } ?>

    <div class="col-md-<?= $col ?>">
        <div class="form-group">
            <label class="control-label">&nbsp;</label>
            <div class="form-control-static no-padding">
                <?= Html::submitButton(Yii::p('cii', 'Add'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<hr>


<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'tableOptions' => [
            'class' => "table table-striped table-bordered table-hover"
        ],
        
        'dataProvider' => $visibilities,
        'columns' => [
            'ordering',
            'position',
            'route_id',
            [
                'attribute' => 'language_id',
                'format' => 'html',
                'value' => ['cii\helpers\Html', 'languageLink'],
                'visible' => $multilanguage
            ],

            [
                'class' => 'cii\grid\ActionColumn',
                'template' => '{delete}',
                'appendixRoute' => 'modules/cii/content'
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>