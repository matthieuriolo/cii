<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\cii\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <?= Html::a(Yii::t('app', 'Create User'), [Yii::$app->seo->relativeAdminRoute('modules/cii/user/create')], ['class' => 'btn btn-success pull-right']) ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <p class="lead">User managment</p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'tableOptions' => [
            'class' => "table table-striped table-bordered table-hover",
            'data-controller' => 'singlerowclick'
        ],
        
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'rowOptions' => function($model, $key, $index, $grid) {
            return $model->superadmin ? ['class' => "warning"] : [];
        },

        'columns' => [
            'username',
            'email:email',
            'created:datetime',
            'activated:datetime',
            'enabled:boolean',
            // 'password',
            // 'enabled',
            // 'language_id',
            // 'layout_id',
            // 'reset_token',
            // 'activation_token',

            [
                'class' => 'cii\grid\ActionColumn',
                'visibleButtons' => [
                    'update' => function($model, $key, $index) {
                        return !$model->superadmin;
                    },

                    'delete' => function($model, $key, $index) {
                        return !$model->superadmin;
                    }
                ],
                'appendixRoute' => 'modules/cii/user'
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
