<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
?>


<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'group_id')->dropDownList($groups, ['options' => $groupOptions]); ?>
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

<?php
Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $data,
        'columns' => [
            'group.name',
            'created',
            'group.enabled:boolean',
            
            [
                'class' => 'cii\grid\ActionColumn',
                'appendixRoute' => 'modules/cii/group',
                'template' => '{delete}',
                'urlCreator' => function($action, $model, $key, $index) {
                    $route = [\Yii::$app->seo->relativeAdminRoute('modules/cii/user/deletemember'), ['id' => $model['id']]];
                    return \Yii::$app->urlManager->createUrl($route);
                },
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
