<?php

use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\data\ArrayDataProvider;
use cii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'Packages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <?php echo Html::a(
        Yii::t('yii', 'Install Package'),
        [\Yii::$app->seo->relativeAdminRoute('modules/cii/package/install')],
        ['class' => 'btn btn-success pull-right']
    ); ?>
    
    <h1>Packages</h1>

    <?php 

    Pjax::begin();
    echo GridView::widget([
        'tableOptions' => [
            'class' => "table table-striped table-bordered table-hover",
            'data-controller' => 'singlerowclick'
        ],

        'dataProvider' => $data,
        
        'rowOptions' => function($model, $key, $index, $grid) {
            return $model->name == 'cii' ? ['class' => "warning"] : [];
        },

        'columns' => [
            'name',
            'installed:datetime',
            'enabled:boolean',
            [
                'class' => ActionColumn::className(),
                'template' => '{view}{disable}{enable}{deinstall}',
                'urlCreator' => function($action, $model, $key, $index) {
                    if($action === 'view') {
                        $route = [\Yii::$app->seo->relativeAdminRoute('modules/cii/package/view'), ['id' => $model->id]];
                    }else if($action === 'disable') {
                        $route = [\Yii::$app->seo->relativeAdminRoute('modules/cii/package/disable'), ['id' => $model->id, 'back' => \Yii::$app->request->getAbsoluteUrl()]];
                    }else if($action === 'enable') {
                        $route = [\Yii::$app->seo->relativeAdminRoute('modules/cii/package/enable'), ['id' => $model->id, 'back' => \Yii::$app->request->getAbsoluteUrl()]];
                    }else if($action === 'deinstall') {
                        $route = [\Yii::$app->seo->relativeAdminRoute('modules/cii/package/deinstall'), ['id' => $model->id, 'back' => \Yii::$app->request->getAbsoluteUrl()]];
                    }
                    
                    return \Yii::$app->urlManager->createUrl($route);
                },

                'buttons' => [
                    'disable' => function($url, $model, $key) {
                        if(
                            !$model->enabled
                            ||
                            $model->name == 'cii'
                        ) {
                            return '';
                        }

                        $options = [
                            'title' => Yii::t('yii', 'Disable'),
                            'aria-label' => Yii::t('yii', 'Disable'),
                            'data-pjax' => '0',
                        ];

                        return Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, $options);
                    },

                    'enable' => function($url, $model, $key) {
                        if(
                            $model->enabled
                            ||
                            $model->name == 'cii'
                        ) {
                            return '';
                        }

                        $options = [
                            'title' => Yii::t('yii', 'Enable'),
                            'aria-label' => Yii::t('yii', 'Enable'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, $options);
                    },

                    'deinstall' => function($url, $model, $key) {
                        if($model->name == 'cii') {
                            return '';
                        }

                        $options = [
                            'title' => Yii::t('yii', 'Update'),
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                    }
                ]
            ],
        ],
    ]);
    Pjax::end(); ?>
</div>
