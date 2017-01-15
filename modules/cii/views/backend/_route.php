<?php

use app\modules\cii\widgets\TabbedPanel;
use app\modules\cii\widgets\LineFlot;
use app\modules\cii\widgets\PieFlot;
use app\modules\cii\models\common\Route;

use cii\helpers\Plotter;
use cii\helpers\Html;

?>
<div class="form-group text-right">
<?php
echo Html::a(Yii::p('cii', 'Routes'), [Yii::$app->seo->relativeAdminRoute('modules/cii/route/index')], ['class' => 'btn btn-sm btn-success']);
?>
</div>
<?php

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
            'title' => Yii::p('cii', 'Top ten (hit rates)'),

            'items' => [
                [
                    'label' => Yii::p('cii', 'Weekly'),
                    'content' => PieFlot::widget([
                        'segments' => Plotter::transformToFlotDatetime(Route::yearlyViewStats())
                    ]),
                ],

                [
                    'label' => Yii::p('cii', 'Monthly'),
                    'content' => PieFlot::widget([
                        'segments' => Plotter::transformToFlotDatetime(Route::yearlyViewStats())
                    ]),
                ],

                [
                    'label' => Yii::p('cii', 'Yearly'),
                    'content' => PieFlot::widget([
                        'segments' => Plotter::transformToFlotDatetime(Route::yearlyViewStats())
                    ]),
                ],
            ]
        ]);
    ?>
    </div>
    <div class="col-md-6"><?php
        echo TabbedPanel::widget([
            'title' => Yii::p('cii', 'Top ten (bounce rates)'),
            
            'items' => [
                [
                    'label' => Yii::p('cii', 'Weekly'),
                    'content' => PieFlot::widget([
                        'segments' => Plotter::transformToFlotDatetime(Route::yearlyViewStats())
                    ]),
                ],

                [
                    'label' => Yii::p('cii', 'Monthly'),
                    'content' => PieFlot::widget([
                        'segments' => Plotter::transformToFlotDatetime(Route::yearlyViewStats())
                    ]),
                ],

                [
                    'label' => Yii::p('cii', 'Yearly'),
                    'content' => PieFlot::widget([
                        'segments' => Plotter::transformToFlotDatetime(Route::yearlyViewStats())
                    ]),
                ],
            ],
        ]);
    ?></div>
</div>