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
                    'name' => 'Dashboard',
                    'url' => [Yii::$app->seo->relativeAdminRoute('index')],
                    'icon' => 'glyphicon glyphicon-blackboard',
                ],

                [
                    'name' => 'Web media & access',
                    'icon' => 'glyphicon glyphicon-globe',
                    'children' => [
                        [
                            'name' => 'Routes',
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/route/index'), []],
                            'icon' => 'glyphicon glyphicon-link'
                        ],

                        [
                            'name' => 'Contents',
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
                    'name' => 'Authentication',
                    'icon' => 'glyphicon glyphicon-lock',
                    'children' => [
                        [
                            'name' => 'Users',
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/user/index'), []],
                            'icon' => 'glyphicon glyphicon-user'
                        ],

                        [
                            'name' => 'Groups',
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/group/index'), []],
                            'icon' => 'glyphicon glyphicon-tags'
                        ],
                    ]
                ],

                [
                    'name' => 'Application',
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
                            'name' => 'Settings',
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/setting/index'), []],
                            'icon' => 'glyphicon glyphicon-wrench'
                        ],

                        [
                            'name' => 'Log',
                            'url' => [Yii::$app->seo->relativeAdminRoute('log'), []],
                            'icon' => 'glyphicon glyphicon-record'
                        ],
                    ],
                ],

                [
                    'name' => 'Extensions',
                    'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/extension/index')],
                    'icon' => 'glyphicon glyphicon-tasks',
                    'children' => [
                        [
                            'name' => 'Packages',
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/package/index')],
                            'icon' => 'glyphicon glyphicon-gift'
                        ],

                        [
                            'name' => 'Languages',
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/language/index'), []],
                            'icon' => 'glyphicon glyphicon-flag'
                        ],

                        [
                            'name' => 'Layouts',
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