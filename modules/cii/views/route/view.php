<?php

//use Yii;
use cii\helpers\Html;
use yii\bootstrap\Tabs;
use cii\helpers\SPL;


use cii\widgets\Pjax;
use cii\widgets\PjaxBreadcrumbs;

$outbox = $model->outbox();

$this->title = Yii::p('cii', 'Route') . ' - ' . $model->slug;

$this->params['breadcrumbs'][] = [
    'label' => Yii::p('cii', 'Routes'),
    'url' => [\Yii::$app->seo->relativeAdminRoute('route/index'), ['parent' => $model->parent_id]]
];
$this->params['breadcrumbs'][] = $this->title;


$pjaxid = Yii::$app->request->pjaxid();
if($pjaxid) {
    echo PjaxBreadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        'pjaxid' => $pjaxid,
    ]);
}



$items = [
    [
        'encode' => false,
        'label' => '<i class="glyphicon glyphicon-link"></i> ' . Yii::p('cii', 'Route'),
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
        <?= Html::a(Yii::p('cii', 'Update'), [
            Yii::$app->seo->relativeAdminRoute('route/update'),
            'id' => $model->id
            ],
            [
                'class' => 'btn btn-primary'
        ]) ?>
        <?= Html::a(Yii::p('cii', 'Delete'), [
            Yii::$app->seo->relativeAdminRoute('route/delete'),
            'id' => $model->id
        ], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::p('cii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

	<h1><?= Html::encode($this->title) ?></h1>

    <div class="body-content">
    	<?php echo Tabs::widget([
            'id' => uniqid(),
            'items' => $items,
        ]); ?>
	</div>
</div>

