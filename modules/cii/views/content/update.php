<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use yii\widgets\Pjax;

use cii\widgets\PjaxBreadcrumbs;
use cii\helpers\SPL;

$this->title = Yii::p('cii', 'Update {modelClass} - ', [
    'modelClass' => Yii::p('cii', 'Content'),
]) . $model->name;
$this->params['breadcrumbs'][] = [
	'label' => Yii::p('cii', 'Contents'),
	'url' => [Yii::$app->seo->relativeAdminRoute('content/index')]
];
$this->params['breadcrumbs'][] = [
	'label' => $model->name,
	'url' => [Yii::$app->seo->relativeAdminRoute('content/view'), 'id' => $model->id]
];
$this->params['breadcrumbs'][] = Yii::p('cii', 'Update');



if($pjaxid) {
    Pjax::begin([
        'id' => $pjaxid,
    ]);

    echo PjaxBreadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        'pjaxid' => $pjaxid,
    ]);
}
?>
<div class="content-update">
	<?php $form = ActiveForm::begin([
        'action' => [
            Yii::$app->seo->relativeAdminRoute('content/update'),
            'id' => $model->id,
        ] + ($pjaxid ? ['pjaxid' => $pjaxid] : []),
        'options' => [
            'data-pjax' => (bool)$pjaxid
        ]
    ]); ?>

	<div class="form-group pull-right">
		<?php
		echo Html::a(
            Yii::p('cii', 'Cancel'),
            [
                \Yii::$app->seo->relativeAdminRoute('content/index')
            ] + ($pjaxid ? ['pjaxid' => $pjaxid]: []),
            ['class' => 'btn btn-warning']
        ),
        '&nbsp;',
        Html::submitButton(Yii::p('cii', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="body-content">
        <?php
        $items = [
            [
                'encode' => false,
                'label' => '<i class="glyphicon glyphicon-file"></i> ' . Yii::p('cii', 'Content'),
                'content' => $this->render('_form', [
                    'model' => $model,
                    'form' => $form,
                    'types' => $types,
                    'pjaxid' => $pjaxid,
                ])
            ]
        ];

        if($topmodel) {
            $info = $topmodel->className();
            if(SPL::hasInterface($topmodel, 'app\modules\cii\base\LazyModelInterface') && $info::hasLazyCRUD()) {
                $info = $info::getLazyCRUD();
                
                array_push($items, [
                    'encode' => false,
                    'label' => $info['controller']->$info['label'](),
                    'content' => $info['controller']->$info['update']($topmodel, $form),
                    'pjaxid' => $pjaxid,
                ]);
            }
        }

        echo Tabs::widget([
            'id' => uniqid(),
            'items' => $items
        ]); ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
if($pjaxid) {
    Pjax::end();
}