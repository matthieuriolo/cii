<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;

$form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-md-3">
        <?= $form->field($visibleModel, 'position')->textInput(); ?>
    </div>
    
    <div class="col-md-3">
        <?= $form->field($visibleModel, 'route_id')->dropDownList($routes); ?>
    </div>
    
    <div class="col-md-3">
        <?= $form->field($visibleModel, 'language_id')->dropDownList($languages); ?>
    </div>
    
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">&nbsp;</label>
            <div class="form-control-static no-padding">
                <?= Html::submitButton(Yii::t('app', 'Add'), ['class' => 'btn btn-primary']) ?>
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
            'position',
            'route_id',
            'language_id',

            [
                'class' => 'cii\grid\ActionColumn',
                'template' => '{delete}',
                'appendixRoute' => 'modules/cii/content'
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>