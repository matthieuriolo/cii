<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::p('cii', 'Contents');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-index">
    <?= Html::a(Yii::p('cii', 'Create Content'), [\Yii::$app->seo->relativeAdminRoute('modules/cii/content/create')], ['class' => 'btn btn-success pull-right']) ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <p class="lead"><?= Yii::p('cii', 'Contents define what data can be accessed (usually through routes)'); ?></p>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'tableOptions' => [
            'class' => "table table-striped table-bordered table-hover",
            'data-controller' => 'singlerowclick'
        ],
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'name',
            [
                'attribute' => 'classname',
                'value' => 'classname.typename',
            ],
            
            'created:datetime',
            'enabled:boolean',

            [
                'class' => 'cii\grid\ActionColumn',
                'appendixRoute' => 'modules/cii/content'
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
</div>
