<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::p('cii', 'Groups');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::a(Yii::p('cii', 'Create Group'), [Yii::$app->seo->relativeAdminRoute('group/create')], ['class' => 'btn btn-success pull-right']) ?>

<h1><?= Html::encode($this->title) ?></h1>

<p class="lead"><?= Yii::p('cii', 'User can be assigned to a group granting it a set of permissions'); ?></p>

<?php Pjax::begin(); ?>
    
    <?= $model->render($this); ?>

    <?= GridView::widget([
        'tableOptions' => [
            'class' => "table table-striped table-bordered table-hover",
            'data-controller' => 'singlerowclick'
        ],
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'name',
            'created:datetime',
            'enabled:boolean',
            
            [
                'class' => 'cii\grid\ActionColumn',
                'headerOptions' => ['class' => 'action-column column-width-3'],
                'appendixRoute' => 'group',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
