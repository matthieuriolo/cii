<?php 

namespace app\modules\cii;
use Yii;


class Menu {
    static protected function visible($module, $permissions) {
        $permissions = (array)$permissions;

        foreach($permissions as $permission) {
            if(Yii::$app->user->can([$module->getIdentifier(), $permission])) {
                return true;
            }
        }

        return false;
    }

    static public function getBackendItems($module) {
        return [
            'name' => 'Cii Core',
            'url' => [Yii::$app->seo->relativeAdminRoute('package'), ['name' => $module->id]],
            'icon' => 'glyphicon glyphicon-home',
            'visible' => true,
            'children' => [
                [
                    'name' => Yii::p('cii', 'Dashboard'),
                    'url' => [Yii::$app->seo->relativeAdminRoute('index')],
                    'icon' => 'glyphicon glyphicon-blackboard',
                    'visible' => self::visible($module, Permission::MANAGE_ADMIN),
                ],

                [
                    'name' => 'Web media & access',
                    'icon' => 'glyphicon glyphicon-globe',
                    'visible' => self::visible($module, [Permission::MANAGE_ADMIN, Permission::MANAGE_ROUTE, Permission::MANAGE_CONTENT]),
                    'children' => [
                        [
                            'name' => Yii::p('cii', 'Routes'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/route/index'), []],
                            'icon' => 'glyphicon glyphicon-link',
                            'visible' => self::visible($module, [Permission::MANAGE_ADMIN, Permission::MANAGE_ROUTE]),
                        ],

                        [
                            'name' => Yii::p('cii', 'Contents'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/content/index'), []],
                            'icon' => 'glyphicon glyphicon-file',
                            'visible' => self::visible($module, [Permission::MANAGE_ADMIN, Permission::MANAGE_CONTENT]),
                        ],

                        /*
                        [
                            'name' => Yii::p('cii', 'Positions'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/position/index'), []],
                            'icon' => 'glyphicon glyphicon-blackboard'
                        ],

                        
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
                    'visible' => self::visible($module, [Permission::MANAGE_ADMIN, Permission::MANAGE_USER, Permission::MANAGE_GROUP]),
                    'children' => [
                        [
                            'name' => Yii::p('cii', 'Users'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/user/index'), []],
                            'icon' => 'glyphicon glyphicon-user',
                            'visible' => self::visible($module, [Permission::MANAGE_ADMIN, Permission::MANAGE_USER]),
                        ],

                        [
                            'name' => Yii::p('cii', 'Groups'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/group/index'), []],
                            'icon' => 'glyphicon glyphicon-tags',
                            'visible' => self::visible($module, [Permission::MANAGE_ADMIN, Permission::MANAGE_GROUP]),
                        ],

                        /*[
                            'name' => Yii::p('cii', 'Mandante'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/mandate/index'), []],
                            'icon' => 'glyphicon glyphicon-piggy-bank',
                            'visible' => self::visible($module, [Permission::MANAGE_ADMIN]),
                        ],
                        */
                    ]
                ],

                [
                    'name' => Yii::p('cii', 'Application'),
                    'icon' => 'glyphicon glyphicon-cog',
                    'visible' => self::visible($module, [Permission::MANAGE_ADMIN, Permission::MANAGE_SETTING, Permission::MANAGE_LOG]),
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
                            'icon' => 'glyphicon glyphicon-wrench',
                            'visible' => self::visible($module, [Permission::MANAGE_ADMIN, Permission::MANAGE_SETTING]),
                        ],

                        [
                            'name' => Yii::p('cii', 'Log & Cache'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('log'), []],
                            'icon' => 'glyphicon glyphicon-record',
                            'visible' => self::visible($module, [Permission::MANAGE_ADMIN, Permission::MANAGE_LOG]),
                        ],
                    ],
                ],

                [
                    'name' => Yii::p('cii', 'Extensions'),
                    'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/extension/index')],
                    'icon' => 'glyphicon glyphicon-tasks',
                    'visible' => self::visible($module, [Permission::MANAGE_ADMIN, Permission::MANAGE_EXTENSION, Permission::MANAGE_PACKAGE, Permission::MANAGE_LAYOUT, Permission::MANAGE_LANGUAGE]),
                    'children' => [
                        [
                            'name' => Yii::p('cii', 'Packages'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/package/index')],
                            'icon' => 'glyphicon glyphicon-gift',
                            self::visible($module, [Permission::MANAGE_ADMIN, Permission::MANAGE_EXTENSION, Permission::MANAGE_PACKAGE]),
                        ],

                        [
                            'name' => Yii::p('cii', 'Languages'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/language/index'), []],
                            'icon' => 'glyphicon glyphicon-flag',
                            self::visible($module, [Permission::MANAGE_ADMIN, Permission::MANAGE_EXTENSION, Permission::MANAGE_LANGUAGE]),
                        ],

                        [
                            'name' => Yii::p('cii', 'Layouts'),
                            'url' => [Yii::$app->seo->relativeAdminRoute('modules/cii/layout/index'), []],
                            'icon' => 'glyphicon glyphicon-picture',
                            self::visible($module, [Permission::MANAGE_ADMIN, Permission::MANAGE_EXTENSION, Permission::MANAGE_LAYOUT]),
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