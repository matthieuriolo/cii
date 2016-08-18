<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use cii\helpers\SPL;


$this->title = Yii::t('app', 'Update {modelClass} - ', [
    'modelClass' => 'Route',
]) . $model->slug;
$this->params['breadcrumbs'][] = [
	'label' => Yii::t('app', 'Routes'),
	'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/route/index')]
];
$this->params['breadcrumbs'][] = [
	'label' => $model->slug,
	'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/route/view'), 'id' => $model->id]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="route-update">
	<?php $form = ActiveForm::begin(); ?>
	
	<div class="form-group pull-right">
		<?php echo Html::a(
	        Yii::t('yii', 'Cancel'),
	        [Yii::$app->seo->relativeAdminRoute('modules/cii/route/index')],
	        ['class' => 'btn btn-warning']
	    ); ?>

        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

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
