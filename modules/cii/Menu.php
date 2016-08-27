<?php 

namespace app\modules\cii;
use Yii;


class Menu {
    static public function getBackendItems($module) {
        return [
            'name' => 'Cii Core',
            'url' => [Yii::$app->seo->relativeAdminRoute('package'), ['name' => $module->id]],
            'icon' => 'glyphicon glyphicon-home',
            'children' => [
                [
                    'name' => Yii::p('cii', 'Dashboard'),
                    'url' => [Yii::$app->seo->relativeAdminRoute('index')],
                    'icon' => 'glyphicon glyphicon-blackboard',
                ],

                [
                    'name' => 'Web media & access',
                    'icon' => 'glyphicon glyphicon-globe',
                    'children' => [
                        [
                            'name' => Yii::p('cii', 'Routes'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/route/index'), []],
                            'icon' => 'glyphicon glyphicon-link'
                        ],

                        [
                            'name' => Yii::p('cii', 'Contents'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/content/index'), []],
                            'icon' => 'glyphicon glyphicon-file'
                        ],

                        /*
                        [
                            'name' => 'Mail templates',
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/mail/index')],
                            'icon' => 'glyphicon glyphicon-envelope'
                        ],
                        */
                    ],
                ],

                [
                    'name' => Yii::p('cii', 'Authentication'),
                    'icon' => 'glyphicon glyphicon-lock',
                    'children' => [
                        [
                            'name' => Yii::p('cii', 'Users'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/user/index'), []],
                            'icon' => 'glyphicon glyphicon-user'
                        ],

                        [
                            'name' => Yii::p('cii', 'Groups'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/group/index'), []],
                            'icon' => 'glyphicon glyphicon-tags'
                        ],
                    ]
                ],

                [
                    'name' => Yii::p('cii', 'Application'),
                    'icon' => 'glyphicon glyphicon-cog',
                    'children' => [
                        /*[
                            'name' => 'Backups',
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/user/index'), []],
                            'icon' => 'glyphicon glyphicon-hdd'
                        ],
                        
                        [
                            'name' => 'Defaults',
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/user/index'), []],
                            'icon' => 'glyphicon glyphicon-tint'
                        ],
                        */
                        [
                            'name' => Yii::p('cii', 'Settings'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/setting/index'), []],
                            'icon' => 'glyphicon glyphicon-wrench'
                        ],

                        [
                            'name' => Yii::p('cii', 'Log'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('log'), []],
                            'icon' => 'glyphicon glyphicon-record'
                        ],
                    ],
                ],

                [
                    'name' => Yii::p('cii', 'Extensions'),
                    'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/extension/index')],
                    'icon' => 'glyphicon glyphicon-tasks',
                    'children' => [
                        [
                            'name' => Yii::p('cii', 'Packages'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/package/index')],
                            'icon' => 'glyphicon glyphicon-gift'
                        ],

                        [
                            'name' => Yii::p('cii', 'Languages'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/language/index'), []],
                            'icon' => 'glyphicon glyphicon-flag'
                        ],

                        [
                            'name' => Yii::p('cii', 'Layouts'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/layout/index'), []],
                            'icon' => 'glyphicon glyphicon-picture'
                        ],

                        /*
                        [
                            'name' => 'Editors',
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/layout/index'), []],
                            'icon' => 'glyphicon glyphicon-console'
                        ],

                        [
                            'name' => 'Plugins',
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/layout/index'), []],
                            'icon' => 'glyphicon glyphicon-bell'
                        ],

                        [
                            'name' => 'Tasks',
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/layout/index'), []],
                            'icon' => 'glyphicon glyphicon-time'
                        ],*/
                    ]
                ],
            ]
        ];
    }
}