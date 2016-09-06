<?php

use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\data\ArrayDataProvider;
use cii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;


$this->title = Yii::p('cii', 'Routes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <?php echo Html::a(
        Yii::p('cii', 'Create Route'),
        [\Yii::$app->seo->relativeAdminRoute('modules/cii/route/create'), ['parent' => $parent ? $parent->id : null]],
        ['class' => 'btn btn-success pull-right']
    ); ?>

    <h1><?= Yii::p('cii', 'Routes') ?></h1>
    
    <p class="lead"><?= Yii::p('cii', 'Routes define how the URL looks like and which content can be accessed') ?></p>

    <?php
    if($parent) {
        $slugs = $parent->getParentChain();
        $links = array_map(function($route) {
            return [
                'label' => $route->slug,
                'url' => [\Yii::$app->seo->relativeAdminRoute('modules/cii/route/index'), ['parent' => $route->parent_id]]
            ];
        }, $slugs);

        echo Breadcrumbs::widget([
            'homeLink' => false,
            'links' => $links
        ]);
    }
    ?>
   
    <?php 
    Pjax::begin();

    echo $model->render($this);

    echo GridView::widget([
        'tableOptions' => [
            'class' => "table table-striped table-bordered table-hover",
            'data-controller' => 'singlerowclick'
        ],
        'dataProvider' => $data,
        'columns' => [
            'slug',
            
            [
                'attribute' => 'classname',
                'value' => 'classname.typename',
            ],

            [
                'attribute' => 'language',
                'value' => 'language.name',
                'visible' => Yii::$app->cii->package->setting('cii', 'multilanguage')
            ],

            'hits',
            'created:datetime',
            'enabled:boolean',
            
            [
                'class' => ActionColumn::className(),
                'template' => '{view}{update}{delete}{children}',
                'urlCreator' => function($action, $model, $key, $index) {
                    if($action == 'view') {
                        $route = [\Yii::$app->seo->relativeAdminRoute('modules/cii/route/view'), ['id' => $model['id']]];
                    }else if($action == 'children') {
                        $route = [\Yii::$app->seo->relativeAdminRoute('modules/cii/route/index'), ['parent' => $model['id']]];
                    }else if($action == 'delete') {
                        $route = [\Yii::$app->seo->relativeAdminRoute('modules/cii/route/delete'), ['id' => $model['id']]];
                    }else if($action == 'update') {
                        $route = [\Yii::$app->seo->relativeAdminRoute('modules/cii/route/update'), ['id' => $model['id']]];
                    }

                    return \Yii::$app->urlManager->createUrl($route);
                },

                'buttons' => [
                    'children' => function($url, $model, $key) {
                        if(!$model->hasChildren()) {
                            return '';
                        }

                        $options = [
                            'title' => Yii::p('cii', 'Disable'),
                            'aria-label' => Yii::p('cii', 'Disable'),
                            'data-pjax' => '0',
                        ];

                        return Html::a('<span class="glyphicon glyphicon-open"></span>', $url, $options);
                    }
                ]
            ],
        ],
    ]);

    Pjax::end(); ?>
</div>
