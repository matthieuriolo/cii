<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

$this->title = Yii::p('cii', 'Positions');
$this->params['breadcrumbs'][] = $this->title;

$layoutName = Yii::$app->cii->package->setting('cii', 'layout');
$layout = Yii::$app->cii->layout->getReflection($layoutName);

$items = [
    [
        'encode' => false,
        'label' => '<i class="glyphicon glyphicon-blackboard"></i> ' . Yii::p('cii', 'Position'),
        'content' => $this->render('_list', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'layout' => $layout
        ])
    ],
];

if($view = $layout->getOverviewView()) {
    $items[] = [
        'encode' => false,
        'label' => '<i class="glyphicon glyphicon-th"></i> ' . Yii::p('cii', 'Position overview'),
        'content' => $this->render('@app/layouts/' . $layoutName . '/' . $view)
    ];
}

?>

<?= Html::a(Yii::p('cii', 'Add Position'), [Yii::$app->seo->relativeAdminRoute('modules/cii/position/create')], ['class' => 'btn btn-success pull-right']) ?>
<h1><?= Html::encode($this->title) ?></h1>

<p class="lead"><?= Html::encode(Yii::p('cii', 'Position managment')); ?></p>


<?= Tabs::widget([
        'items' => $items
    ]);
?>