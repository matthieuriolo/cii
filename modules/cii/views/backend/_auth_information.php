<?php

use app\modules\cii\widgets\TabbedPanel;
use app\modules\cii\widgets\LineFlot;
use app\modules\cii\widgets\PieFlot;
use app\modules\cii\models\auth\User;
use app\modules\cii\models\auth\Group;

use cii\helpers\Plotter;
use cii\helpers\Html;

use cii\grid\GridView;
use yii\data\ArrayDataProvider;


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
                        
                        'dataProvider' => new ArrayDataProvider(['key' => 'id', 'allModels' => User::toptenCreated()]),
                        'rowOptions' => function($model, $key, $index, $grid) {
                            return $model->superadmin ? ['class' => "warning"] : [];
                        },
                        
                        'summary' => false,
                        'showHeader' => false,

                        'columns' => [
                            'username',
                            'created:relativeTime',
                            
                            [
                                'class' => 'cii\grid\ActionColumn',
                                'template' => '{view}',
                                'contentOptions' => ['class' => 'action-column column-width-1'],
                                'appendixRoute' => 'user'
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

                        'dataProvider' => new ArrayDataProvider(['key' => 'id', 'allModels' => User::toptenLastLogin()]),
                        'rowOptions' => function($model, $key, $index, $grid) {
                            return $model->superadmin ? ['class' => "warning"] : [];
                        },

                        'columns' => [
                            'username',
                            'last_login:relativeTime',

                            [
                                'class' => 'cii\grid\ActionColumn',
                                'template' => '{view}',
                                'contentOptions' => ['class' => 'action-column column-width-1'],
                                'appendixRoute' => 'user'
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
                        
                        'dataProvider' => new ArrayDataProvider(['key' => 'id', 'allModels' => Group::toptenCreated()]),
                        'columns' => [
                            'name',
                            'created:relativeTime',

                            [
                                'class' => 'cii\grid\ActionColumn',
                                'template' => '{view}',
                                'contentOptions' => ['class' => 'action-column column-width-1'],
                                'appendixRoute' => 'group'
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
                        
                        'showHeader' => false,
                        'summary' => false,

                        'dataProvider' => new ArrayDataProvider(['key' => 'id', 'allModels' => Group::toptenCountMembers()]),
                        'columns' => [
                            'name',
                            'countMembers:integer',

                            [
                                'class' => 'cii\grid\ActionColumn',
                                'template' => '{view}',
                                'contentOptions' => ['class' => 'action-column column-width-1'],
                                'appendixRoute' => 'group'
                            ],
                        ],
                    ]),
                ],
            ],
        ]);
    ?></div>
</div>
