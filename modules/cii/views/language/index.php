<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\core\models\LanguageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Languages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="language-index">
    <div class="pull-right">
        <?= Html::a(Yii::t('app', 'Install Language'), [\Yii::$app->seo->relativeAdminRoute('modules/cii/language/install')], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Create Language'), [\Yii::$app->seo->relativeAdminRoute('modules/cii/language/create')], ['class' => 'btn btn-success']) ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'tableOptions' => [
            'class' => "table table-striped table-bordered table-hover",
            'data-controller' => 'singlerowclick'
        ],
        
        'dataProvider' => $data,

        'columns' => [
            'name',
            'enabled:boolean',
            'code',
            'shortcode',

            [
                'class' => 'cii\grid\ActionColumn',
                'appendixRoute' => 'modules/cii/language'
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
