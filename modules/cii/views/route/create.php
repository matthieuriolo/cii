<?php
use cii\helpers\Html;
use yii\bootstrap\Dropdown;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use cii\helpers\SPL;



$this->title = 'Create route';


$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Routes'),
    'url' => [\Yii::$app->seo->relativeAdminRoute('modules/cii/route/index'), ['parent' => $parentId]]
];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-index">
	<?php $form = ActiveForm::begin(); ?>

	<div class="form-group pull-right">
		<?php
		$url = \Yii::$app->seo->relativeAdminRoute('modules/cii/route/index');
		if($parentId) {
			$url = [$url, ['parent' => $parentId]];
		}else {
			$url = [$url];
		}
		echo Html::a(
	        Yii::t('yii', 'Cancel'),
	        $url,
	        ['class' => 'btn btn-warning']
	    ); ?>

	    <?php echo Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']); ?>
    </div>

	<h1>Create Route</h1>

    <div class="body-content">
		<?php

		$items = [
            [
                'encode' => false,
                'label' => '<i class="glyphicon glyphicon-link"></i> Route',
                'content' => $this->render('_form', [
                	'model' => $model,
                	'form' => $form,
                	'types' => $types,
                	'parentRoutes' => $parentRoutes,
                	'languages' => $languages
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

