<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\core\models\ContentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Contents');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-index">
    <?= Html::a(Yii::t('app', 'Create Content'), [\Yii::$app->seo->relativeAdminRoute('modules/cii/content/create')], ['class' => 'btn btn-success pull-right']) ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <p class="lead">Contents define what data can be accessed (usually through routes)</p>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


<?php Pjax::begin(); ?>    <?= GridView::widget([
        'tableOptions' => [
            'class' => "table table-striped table-bordered table-hover",
            'data-controller' => 'singlerowclick'
        ],
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'name',
            'enabled:boolean',

            [
                'attribute' => 'classname',
                'value' => 'classname.typename',
            ],

            [
                'class' => 'cii\grid\ActionColumn',
                'appendixRoute' => 'modules/cii/content'
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
