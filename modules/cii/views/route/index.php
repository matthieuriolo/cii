<?php

use yii\data\ArrayDataProvider;
use yii\widgets\Breadcrumbs;


use cii\widgets\Pjax;
use cii\grid\GridView;
use cii\helpers\Url;
use cii\helpers\Html;
use cii\widgets\PjaxBreadcrumbs;
use app\modules\cii\Permission;

$this->title = Yii::p('cii', 'Routes');
$this->params['breadcrumbs'][] = $this->title;

$editable = Yii::$app->user->can(['cii', Permission::MANAGE_ROUTE]) || Yii::$app->user->can(['cii', Permission::MANAGE_ADMIN]);

$pjaxid = Yii::$app->request->pjaxid();
if($pjaxid) {
    echo PjaxBreadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
    ]);
}
?>
<div class="site-index">
    <?php if($editable) { ?>
        <?php echo Html::a(
            Yii::p('cii', 'Create Route'),
            [
                \Yii::$app->seo->relativeAdminRoute('modules/cii/route/create'),
                ['parent' => $parent ? $parent->id : null]
            ],
            ['class' => 'btn btn-success pull-right']
        ); ?>
    <?php } ?>

    <h1><?= Yii::p('cii', 'Routes') ?></h1>
    
    <p class="lead"><?= Yii::p('cii', 'Routes define how the URL looks like and which content can be accessed') ?></p>
   
    <?php 
    Pjax::begin();

    echo $model->render($this);

    if($parent) {
        $slugs = $parent->getParentChain();
        $links = array_map(function($route) {
            return [
                'label' => $route->slug,
                'url' => [\Yii::$app->seo->relativeAdminRoute('modules/cii/route/index'), [
                        'parent' => $route->id
                    ]
                ]
            ];
        }, $slugs);

        $links[count($links) - 1]['url'] = null;

        echo Breadcrumbs::widget([
            'homeLink' => [
                'label' => $_SERVER['SERVER_NAME'],
                'url' => [\Yii::$app->seo->relativeAdminRoute('modules/cii/route/index')],
            ],
            'links' => $links
        ]);
    }

    echo GridView::widget([
        'tableOptions' => [
            'class' => "table table-striped table-bordered table-hover" . ($this->getIsPjax() ? ' table-select': ''),
            'data-controller' => $this->getIsPjax() ? 'dataselect' : 'singlerowclick',
        ],


        'rowOptions' => function($model, $key, $index, $grid) {
            return [
                'data-value' => $model->id,
                'data-url' => Url::to([
                    Yii::$app->seo->relativeAdminRoute('modules/cii/route/view'),
                    'id' => $model->id
                ]),
                'data-name' => Html::encode($model->slug),
            ];
        },

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
                'class' => 'cii\grid\ActionColumn',
                'appendixRoute' => true,
                'template' => '{view} {update} {delete} {children}',
                
                'urlCreator' => function($action, $model, $key, $index) use($pjaxid) {
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

                'headerOptions' => ['class' => 'action-column ' . ($editable ? 'column-width-4' : 'column-width-2')],
                'buttonOptions' => $pjaxid ? ['data-pjax' => '#' . $pjaxid] : [],
                
                'visibleButtons' => [
                    'update' => $editable,
                    'delete' => $editable,
                ],

                
                'buttons' => [
                    'children' => function($url, $model, $key) {
                        if(!$model->hasChildren()) {
                            return '';
                        }

                        $options = [
                            'title' => Yii::p('cii', 'See child routes'),
                            'aria-label' => Yii::p('cii', 'See child routes'),
                        ];

                        return Html::a('<span class="glyphicon glyphicon-open"></span>', $url, $options);
                    }
                ]
            ],
        ],
    ]);

    Pjax::end(); ?>
</div>