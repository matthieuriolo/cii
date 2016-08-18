<?php

//use Yii;
use cii\helpers\Html;
use yii\bootstrap\Tabs;
use cii\helpers\SPL;

$outbox = $model->outbox();

$this->title = 'Route - ' . $model->slug;

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Routes'),
    'url' => [\Yii::$app->seo->relativeAdminRoute('modules/cii/route/index'), ['parent' => $model->parent_id]]
];
$this->params['breadcrumbs'][] = $this->title;





$items = [
    [
        'encode' => false,
        'label' => '<i class="glyphicon glyphicon-link"></i> Route',
        'content' => $this->render('_overview', [
        	'model' => $model
        ])
    ]
];

$info = $outbox->className();
if(SPL::hasInterface($outbox, 'app\modules\cii\base\LazyModelInterface') && $info::hasLazyCRUD()) {
	$info = $info::getLazyCRUD();
	
	array_push($items, [
        'encode' => false,
        'label' => $info['controller']->$info['label'](),
        'content' => $info['controller']->$info['view']($outbox),
    ]);
}


?>

<div class="site-index">
	<p class="pull-right">
        <?= Html::a(Yii::t('app', 'Update'), [Yii::$app->seo->relativeAdminRoute('modules/cii/route/update'), 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), [Yii::$app->seo->relativeAdminRoute('modules/cii/route/delete'), 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

	<h1>Route - <?php echo $model->slug; ?></h1>

    <div class="body-content">
    	<?php echo Tabs::widget(['items' => $items]); ?>
	</div>
</div>

