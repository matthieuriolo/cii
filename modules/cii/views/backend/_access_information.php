<?php

use app\modules\cii\widgets\TabbedPanel;
use app\modules\cii\widgets\LineFlot;
use app\modules\cii\widgets\PieFlot;
use app\modules\cii\models\common\Route;

use cii\helpers\Plotter;
use cii\helpers\Html;

use cii\grid\GridView;
use yii\data\ArrayDataProvider;

echo TabbedPanel::widget([
    'title' => Yii::p('cii', 'Accesses'),
    'content' => Yii::p('cii', 'Shows the view and bounce progress of the whole application'),
    
    'items' => [
        [
            'label' => Yii::p('cii', 'Weekly'),
            'content' => LineFlot::widget([
                'lines' => [
                    [
                        'label' => Yii::p('cii', 'Views'),
                        'data' => Plotter::transformToFlotDatetime(Route::weeklyViewStats())
                    ],

                    [
                        'label' => Yii::p('cii', 'Bounces'),
                        'data' => Plotter::transformToFlotDatetime(Route::weeklyBounceRateStats())
                    ],

                    [
                        'label' => Yii::p('cii', 'Robots'),
                        'data' => Plotter::transformToFlotDatetime(Route::weeklyBotStats())
                    ]
                ]
            ])
        ],

        [
            'label' => Yii::p('cii', 'Monthly'),
            'content' => LineFlot::widget([
                'lines' => [
                    [
                        'label' => Yii::p('cii', 'Views'),
                        'data' => Plotter::transformToFlotDatetime(Route::monthlyViewStats())
                    ],

                    [
                        'label' => Yii::p('cii', 'Bounces'),
                        'data' => Plotter::transformToFlotDatetime(Route::monthlyBounceRateStats())
                    ],

                    [
                        'label' => Yii::p('cii', 'Robots'),
                        'data' => Plotter::transformToFlotDatetime(Route::monthlyBotStats())
                    ]
                ]
            ])
        ],

        [
            'label' => Yii::p('cii', 'Yearly'),
            'content' => LineFlot::widget([
                'lines' => [
                    [
                        'label' => Yii::p('cii', 'Views'),
                        'data' => Plotter::transformToFlotDatetime(Route::yearlyViewStats())
                    ],

                    [
                        'label' => Yii::p('cii', 'Bounces'),
                        'data' => Plotter::transformToFlotDatetime(Route::yearlyBounceRateStats())
                    ],

                    [
                        'label' => Yii::p('cii', 'Robots'),
                        'data' => Plotter::transformToFlotDatetime(Route::yearlyBotStats())
                    ]
                ]
            ])
        ]
    ]
]); ?>



<div class="row">
    <div class="col-md-6"><?php

        echo TabbedPanel::widget([
            'title' => Yii::p('cii', 'Top ten (hits)'),

            'items' => [
                [
                    'label' => Yii::p('cii', 'Weekly'),
                    'content' => GridView::widget([
                        'tableOptions' => [
                            'class' => "table table-striped table-bordered table-hover",
                            'data-controller' => 'singlerowclick'
                        ],
                        
                        'summary' => false,
                        'showHeader' => false,

                        'dataProvider' => new ArrayDataProvider(['key' => 'id', 'allModels' => Route::toptenWeeklyViews()]),
                        
                        'columns' => [
                            'id:route',
                            'weeklyHits:integer',

                            [
                                'class' => 'cii\grid\ActionColumn',
                                'template' => '{view}',
                                'contentOptions' => ['class' => 'action-column column-width-1'],
                                'appendixRoute' => 'route'
                            ],
                        ],
                    ]),
                ],

                [
                    'label' => Yii::p('cii', 'Monthly'),
                    'content' => GridView::widget([
                        'tableOptions' => [
                            'class' => "table table-striped table-bordered table-hover",
                            'data-controller' => 'singlerowclick'
                        ],
                        
                        'summary' => false,
                        'showHeader' => false,

                        'dataProvider' => new ArrayDataProvider(['key' => 'id', 'allModels' => Route::toptenMonthlyViews()]),
                        
                        'columns' => [
                            'id:route',
                            'monthlyHits:integer',
                            
                            [
                                'class' => 'cii\grid\ActionColumn',
                                'template' => '{view}',
                                'contentOptions' => ['class' => 'action-column column-width-1'],
                                'appendixRoute' => 'route'
                            ],
                        ],
                    ]),
                ],

                [
                    'label' => Yii::p('cii', 'Yearly'),
                    'content' => GridView::widget([
                        'tableOptions' => [
                            'class' => "table table-striped table-bordered table-hover",
                            'data-controller' => 'singlerowclick'
                        ],
                        
                        'summary' => false,
                        'showHeader' => false,

                        'dataProvider' => new ArrayDataProvider(['key' => 'id', 'allModels' => Route::toptenYearlyViews()]),
                        
                        'columns' => [
                            'id:route',
                            'yearlyHits:integer',
                            
                            [
                                'class' => 'cii\grid\ActionColumn',
                                'template' => '{view}',
                                'contentOptions' => ['class' => 'action-column column-width-1'],
                                'appendixRoute' => 'route'
                            ],
                        ],
                    ]),
                ],
            ]
        ]);
    ?>
    </div>
    <div class="col-md-6"><?php
        echo TabbedPanel::widget([
            'title' => Yii::p('cii', 'Top ten (bounces)'),
            
            'items' => [
                [
                    'label' => Yii::p('cii', 'Weekly'),
                    'content' => GridView::widget([
                        'tableOptions' => [
                            'class' => "table table-striped table-bordered table-hover",
                            'data-controller' => 'singlerowclick'
                        ],
                        
                        'summary' => false,
                        'showHeader' => false,

                        'dataProvider' => new ArrayDataProvider(['key' => 'id', 'allModels' => Route::toptenWeeklyBounces()]),
                        
                        'columns' => [
                            'id:route',
                            'weeklyBounces:integer',
                            
                            [
                                'class' => 'cii\grid\ActionColumn',
                                'template' => '{view}',
                                'contentOptions' => ['class' => 'action-column column-width-1'],
                                'appendixRoute' => 'route'
                            ],
                        ],
                    ]),
                ],

                [
                    'label' => Yii::p('cii', 'Monthly'),
                    'content' => GridView::widget([
                        'tableOptions' => [
                            'class' => "table table-striped table-bordered table-hover",
                            'data-controller' => 'singlerowclick'
                        ],
                        
                        'summary' => false,
                        'showHeader' => false,

                        'dataProvider' => new ArrayDataProvider(['key' => 'id', 'allModels' => Route::toptenMonthlyBounces()]),
                        
                        'columns' => [
                            'id:route',
                            'monthlyBounces:integer',
                            
                            [
                                'class' => 'cii\grid\ActionColumn',
                                'template' => '{view}',
                                'contentOptions' => ['class' => 'action-column column-width-1'],
                                'appendixRoute' => 'route'
                            ],
                        ],
                    ]),
                ],

                [
                    'label' => Yii::p('cii', 'Yearly'),
                    'content' => GridView::widget([
                        'tableOptions' => [
                            'class' => "table table-striped table-bordered table-hover",
                            'data-controller' => 'singlerowclick'
                        ],
                        
                        'summary' => false,
                        'showHeader' => false,

                        'dataProvider' => new ArrayDataProvider(['key' => 'id', 'allModels' => Route::toptenYearlyBounces()]),
                        
                        'columns' => [
                            'id:route',
                            'yearlyBounces:integer',
                            
                            [
                                'class' => 'cii\grid\ActionColumn',
                                'template' => '{view}',
                                'contentOptions' => ['class' => 'action-column column-width-1'],
                                'appendixRoute' => 'route'
                            ],
                        ],
                    ]),
                ],
            ],
        ]);
    ?></div>
</div>