<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use cii\helpers\SPL;
use dosamigos\tinymce\TinyMce;

$this->title = Yii::p('cii', 'Create Content');
$this->params['breadcrumbs'][] = [
	'label' => Yii::p('cii', 'Contents'),
	'url' => [\Yii::$app->seo->relativeAdminRoute('modules/cii/content/index')]
];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-create">
	<?php $form = ActiveForm::begin(); ?>
	<div class="form-group pull-right">
		<?php
		echo Html::a(
            Yii::p('cii', 'Cancel'),
            [\Yii::$app->seo->relativeAdminRoute('modules/cii/content/index')],
            ['class' => 'btn btn-warning']
        ),
        '&nbsp;',
        Html::submitButton(Yii::p('cii', 'Create'), ['class' => 'btn btn-success']) ?>
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
