<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use cii\helpers\SPL;


$this->title = Yii::t('app', 'Create Content');
$this->params['breadcrumbs'][] = [
	'label' => Yii::t('app', 'Contents'),
	'url' => [\Yii::$app->seo->relativeAdminRoute('modules/cii/content/index')]
];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-create">
	<?php $form = ActiveForm::begin(); ?>
	<div class="form-group pull-right">
		<?php
		echo Html::a(
            Yii::t('yii', 'Cancel'),
            [\Yii::$app->seo->relativeAdminRoute('modules/cii/content/index')],
            ['class' => 'btn btn-warning']
        ),
        '&nbsp;',
        Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
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
            ],
        ];

        if($topmodel) {
            $info = $topmodel->className();
            if(SPL::hasInterface($info, 'app\modules\cii\base\LazyModelInterface') && $info::hasLazyCRUD()) {
                $info = $info::getLazyCrud();
                $items[] = [
                    'encode' => false,
                    'label' => $info['controller']->$info['label'](),
                    'content' => $info['controller']->$info['create']($topmodel, $form),
                ];
            }
        }

        echo Tabs::widget(['items' => $items]);
        ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
