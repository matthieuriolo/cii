<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use cii\helpers\SPL;


$this->title = Yii::p('cii', 'Update {modelClass} - ', [
    'modelClass' => Yii::p('cii', 'Content'),
]) . $model->name;
$this->params['breadcrumbs'][] = [
	'label' => Yii::p('cii', 'Contents'),
	'url' => [Yii::$app->seo->relativeAdminRoute('index')]
];
$this->params['breadcrumbs'][] = [
	'label' => $model->name,
	'url' => [Yii::$app->seo->relativeAdminRoute('view'), 'id' => $model->id]
];
$this->params['breadcrumbs'][] = Yii::p('cii', 'Update');
?>
<div class="content-update">
	<?php $form = ActiveForm::begin(); ?>

	<div class="form-group pull-right">
		<?php
		echo Html::a(
            Yii::p('cii', 'Cancel'),
            [\Yii::$app->seo->relativeAdminRoute('modules/cii/content/index')],
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
                ]);
            }
        }

        echo Tabs::widget([
            'items' => $items
        ]); ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
