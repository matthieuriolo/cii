<?php
use cii\helpers\Html;
use yii\bootstrap\Dropdown;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use cii\helpers\SPL;


use cii\widgets\Pjax;
use cii\widgets\PjaxBreadcrumbs;


$this->title = Yii::p('cii', 'Create Route');


$this->params['breadcrumbs'][] = [
    'label' => Yii::p('cii', 'Routes'),
    'url' => [\Yii::$app->seo->relativeAdminRoute('route/index'), ['parent' => $parentId]]
];
$this->params['breadcrumbs'][] = $this->title;

$pjaxid = Yii::$app->request->pjaxid();
if($pjaxid) {
    echo PjaxBreadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        'pjaxid' => $pjaxid,
    ]);
}
?>
<div class="site-index">
	<?php $form = ActiveForm::begin(); ?>

	<div class="form-group pull-right">
		<?php
		$url = \Yii::$app->seo->relativeAdminRoute('route/index');
		if($parentId) {
			$url = [$url, ['parent' => $parentId]];
		}else {
			$url = [$url];
		}
		echo Html::a(
	        Yii::p('cii', 'Cancel'),
	        $url,
	        ['class' => 'btn btn-warning']
	    ); ?>

	    <?php echo Html::submitButton(Yii::p('cii', 'Create'), ['class' => 'btn btn-success']); ?>
    </div>

	<h1><?= Html::encode($this->title); ?></h1>

    <div class="body-content">
		<?php

		$items = [
            [
                'encode' => false,
                'label' => '<i class="glyphicon glyphicon-link"></i> ' . Yii::p('cii', 'Route'),
                'content' => $this->render('_form', [
                	'model' => $model,
                	'form' => $form,
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

		echo Tabs::widget([
			'id' => uniqid(),
			'items' => $items
		]);
        ?>
		
	</div>
	<?php ActiveForm::end(); ?>
</div>