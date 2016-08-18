<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use cii\helpers\SPL;


$this->title = Yii::t('app', 'Update {modelClass} - ', [
    'modelClass' => 'Content',
]) . $model->name;
$this->params['breadcrumbs'][] = [
	'label' => Yii::t('app', 'Contents'),
	'url' => [Yii::$app->seo->relativeAdminRoute('index')]
];
$this->params['breadcrumbs'][] = [
	'label' => $model->name,
	'url' => [Yii::$app->seo->relativeAdminRoute('view'), 'id' => $model->id]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="content-update">
	<?php $form = ActiveForm::begin(); ?>

	<div class="form-group pull-right">
		<?php
		echo Html::a(
            Yii::t('yii', 'Cancel'),
            [\Yii::$app->seo->relativeAdminRoute('modules/cii/content/index')],
            ['class' => 'btn btn-warning']
        ),
        '&nbsp;',
        Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="body-content">
        <?php
        $items = [
            [
                'encode' => false,
                'label' => '<i class="glyphicon glyphicon-file"></i> Content',
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
