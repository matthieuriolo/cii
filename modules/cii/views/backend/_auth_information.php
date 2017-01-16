<?php

use app\modules\cii\widgets\TabbedPanel;
use app\modules\cii\widgets\LineFlot;
use app\modules\cii\widgets\PieFlot;
use app\modules\cii\models\auth\User;
use app\modules\cii\models\auth\Group;

use cii\helpers\Plotter;
use cii\helpers\Html;

use cii\grid\GridView;
use yii\data\ActiveDataProvider;


echo TabbedPanel::widget([
    'title' => Yii::p('cii', 'Creation'),
    'content' => Yii::p('cii', 'Shows the creation progress of users'),

    'items' => [
        [
            'label' => Yii::p('cii', 'Weekly'),
            'content' => LineFlot::widget([
                'lines' => [
                    [
                        'data' => Plotter::transformToFlotDatetime(User::weeklyCreationStats())
                    ]
                ]
            ])
        ],

        [
            'label' => Yii::p('cii', 'Monthly'),
            'content' => LineFlot::widget([
                'lines' => [
                    [
                        'data' => Plotter::transformToFlotDatetime(User::monthlyCreationStats())
                    ]
                ]
            ])
        ],

        [
            'label' => Yii::p('cii', 'Yearly'),
            'content' => LineFlot::widget([
                'lines' => [
                    [
                        'data' => Plotter::transformToFlotDatetime(User::yearlyCreationStats())
                    ]
                ]
            ])
        ]
    ]
]); ?>

<div class="row">
    <div class="col-md-6"><?php
        echo TabbedPanel::widget([
            'title' => Yii::p('cii', 'Activity'),
            'content' => Yii::p('cii', 'Activities of the users'),

            'items' => [
                [
                    'label' => Yii::p('cii', 'Last login'),
                    'content' => PieFlot::widget([
                        'segments' => Plotter::transformToFlotSegments(User::lastLoginStats())
                    ]),
                ],

                [
                    'label' => Yii::p('cii', 'Groups'),
                    'content' => PieFlot::widget([
                        'segments' => Plotter::transformToFlotSegments(User::groupStats())
                    ]),
                ],

                /*[
                    'label' => Yii::p('cii', 'Mandates',
                    'content' => PieFlot::widget([
                        'segments' => Plotter::transformToFlotSegments(User::lastLoginStats())
                    ]),
                ],*/
            ]
        ]);
    ?>
    </div>
    <div class="col-md-6"><?php
        echo TabbedPanel::widget([
            'title' => Yii::p('cii', 'Metadata'),
            'content' => Yii::p('cii', 'Metadata about users'),
            
            'items' => [
                [
                    'label' => Yii::p('cii', 'Language'),
                    'content' => PieFlot::widget([
                        'segments' => Plotter::transformToFlotSegments(User::metadataLanguageStats())
                    ])
                ],

                [
                    'label' => Yii::p('cii', 'Timezone'),
                    'content' => PieFlot::widget([
                        'segments' => Plotter::transformToFlotSegments(User::metadataTimezoneStats())
                    ])
                ],

                [
                    'label' => Yii::p('cii', 'Layout'),
                    'content' => PieFlot::widget([
                        'segments' => Plotter::transformToFlotSegments(User::metadataLayoutStats())
                    ])
                ],

                /*[
                    'label' => 'Identities',
                    'content' => PieFlot::widget([
                        'segments' => Plotter::transformToFlotSegments(User::metadataLayoutStats())
                    ])
                ],*/
            ]
        ]);
    ?></div>
</div>




<div class="row">
    <div class="col-md-6"><?php
        echo TabbedPanel::widget([
            'title' => Yii::p('cii', 'Top ten users'),

            'items' => [
                [
                    'label' => Yii::p('cii', 'Newest'),
                    'content' => GridView::widget([
                        'tableOptions' => [
                            'class' => "table table-striped table-bordered table-hover",
                            'data-controller' => 'singlerowclick'
                        ],
                        
                        'dataProvider' => new ActiveDataProvider(['query' => User::find()->orderBy('created')->limit(10)]),
                        'rowOptions' => function($model, $key, $index, $grid) {
                            return $model->superadmin ? ['class' => "warning"] : [];
                        },
                        
                        'summary' => false,
                        'showHeader' => false,

                        'columns' => [
                            'username',
                            'created:datetime',
                            
                            [
                                'class' => 'cii\grid\ActionColumn',
                                'template' => '{view}',
                                'contentOptions' => ['class' => 'action-column column-width-1'],
                                'appendixRoute' => 'modules/cii/user'
                            ],
                        ],
                    ]),
                ],

                [
                    'label' => Yii::p('cii', 'Latest login'),
                    'content' => GridView::widget([
                        'tableOptions' => [
                            'class' => "table table-striped table-bordered table-hover",
                            'data-controller' => 'singlerowclick'
                        ],
                        
                        'summary' => false,
                        'showHeader' => false,

                        'dataProvider' => new ActiveDataProvider(['query' => User::find()->orderBy('last_login')->limit(10)]),
                        'rowOptions' => function($model, $key, $index, $grid) {
                            return $model->superadmin ? ['class' => "warning"] : [];
                        },

                        'columns' => [
                            'username',
                            'last_login:datetime',

                            [
                                'class' => 'cii\grid\ActionColumn',
                                'template' => '{view}',
                                'contentOptions' => ['class' => 'action-column column-width-1'],
                                'appendixRoute' => 'modules/cii/user'
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
            'title' => Yii::p('cii', 'Top ten groups'),
            
            'items' => [
                [
                    'label' => Yii::p('cii', 'Newest'),
                    'content' => GridView::widget([
                        'tableOptions' => [
                            'class' => "table table-striped table-bordered table-hover",
                            'data-controller' => 'singlerowclick'
                        ],
                        'showHeader' => false,
                        'summary' => false,
                        
                        'dataProvider' => new ActiveDataProvider(['query' => Group::find()->orderBy('created')->limit(10)]),
                        'columns' => [
                            'name',
                            'created:datetime',

                            [
                                'class' => 'cii\grid\ActionColumn',
                                'template' => '{view}',
                                'contentOptions' => ['class' => 'action-column column-width-1'],
                                'appendixRoute' => 'modules/cii/group'
                            ],
                        ],
                    ]),
                ],

                [
                    'label' => Yii::p('cii', 'Most members'),
                    'content' => GridView::widget([
                        'tableOptions' => [
                            'class' => "table table-striped table-bordered table-hover",
                            'data-controller' => 'singlerowclick'
                        ],
                        
                        
                        'dataProvider' => new ActiveDataProvider(['query' => Group::find()->orderBy('created')->limit(10)]),
                        'columns' => [
                            'name',
                            
                            [
                                'class' => 'cii\grid\ActionColumn',
                                'template' => '{view}',
                                'headerOptions' => ['class' => 'action-column column-width-1'],
                                'appendixRoute' => 'modules/cii/group'
                            ],
                        ],
                    ]),
                ],
            ],
        ]);
    ?></div>
</div>
