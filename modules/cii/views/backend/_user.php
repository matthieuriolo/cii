<?php

use app\modules\cii\widgets\TabbedPanel;
use app\modules\cii\widgets\LineFlot;
use app\modules\cii\widgets\PieFlot;
use app\modules\cii\models\auth\User;

use cii\helpers\Plotter;
/*
'buttons' => [
        [
            'label' => Yii::p('cii', 'Overview'),
            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/user/index')],
        ],

        [
            'label' => Yii::p('cii', 'Create'),
            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/user/create')],
        ],
    ],

*/
echo TabbedPanel::widget([
    'title' => 'Creation',
    'items' => [
        [
            'label' => 'Weekly',
            'content' => LineFlot::widget([
                'lines' => [
                    [
                        'data' => Plotter::transformToFlotDatetime(User::weeklyCreationStats())
                    ]
                ]
            ])
        ],

        [
            'label' => 'Monthly',
            'content' => LineFlot::widget([
                'lines' => [
                    [
                        'data' => Plotter::transformToFlotDatetime(User::monthlyCreationStats())
                    ]
                ]
            ])
        ],

        [
            'label' => 'Yearly',
            'content' => LineFlot::widget([
                'lines' => [
                    [
                        'data' => Plotter::transformToFlotDatetime(User::yearlyCreationStats())
                    ]
                ]
            ])
        ]
    ],

    'content' => 'Shows the creation progress of users'
]);

echo TabbedPanel::widget([
    'title' => 'Metadata',
    'items' => [
        [
            'label' => 'Language',
            'content' => PieFlot::widget([
                'segments' => Plotter::transformToFlotSegments(User::metadataLanguageStats())
            ])
        ],

        [
            'label' => 'Timezone',
            'content' => PieFlot::widget([
                'segments' => Plotter::transformToFlotSegments(User::metadataTimezoneStats())
            ])
        ],

        [
            'label' => 'Layout',
            'content' => PieFlot::widget([
                'segments' => Plotter::transformToFlotSegments(User::metadataLayoutStats())
            ])
        ],
    ],

    'content' => 'Metadata about users'
]);

