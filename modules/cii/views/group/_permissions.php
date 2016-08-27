<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'value')->dropDownList($permissions, ['options' => $permissionOptions]); ?>
        </div>

        <div class="col-md-4">
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
        'dataProvider' => $data,
        'columns' => [
        	'name',
            
            [
                'class' => 'cii\grid\ActionColumn',
                'template' => '{delete}',
                'appendixRoute' => 'modules/cii/group',
                'urlCreator' => function($action, $model, $key, $index) {
                    $route = [\Yii::$app->seo->relativeAdminRoute('modules/cii/group/deletepermission'), ['id' => $model['id']]];
                    return \Yii::$app->urlManager->createUrl($route);
                },
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
