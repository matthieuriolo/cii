<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\cii\models\GroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Groups');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-index">
    <?= Html::a(Yii::t('app', 'Create Group'), [Yii::$app->seo->relativeAdminRoute('modules/cii/group/create')], ['class' => 'btn btn-success pull-right']) ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="lead">User can be assigned to a group granting it a set of permissions</p>


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
            'created',
            'enabled:boolean',
            
            [
                'class' => 'cii\grid\ActionColumn',
                'appendixRoute' => 'modules/cii/group',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
