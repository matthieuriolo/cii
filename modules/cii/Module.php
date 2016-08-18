<?php 

namespace app\modules\cii;
use Yii;


class Module extends \cii\backend\Package {
    public function getBackendItems() {
    	return [
    		'name' => 'Cii Core',
    		'url' => [Yii::$app->seo->relativeAdminRoute('package'), ['name' => $this->id]],
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

    public function getRouteTypes() {
    	return [
    		'app\modules\cii\models\ContentRoute' => 'Content',
    		'app\modules\cii\models\ProfileRoute' => 'Profile',

    		'app\modules\cii\models\BackendRoute' => 'Administrator',
    		'app\modules\cii\models\GiiRoute' => 'Gii generator',
    		'app\modules\cii\models\DocRoute' => 'Documentation',
    	];
    }

    public function getContentTypes() {
    	return [
    		'app\modules\cii\models\UserLoginContent' => 'Login',
    		'app\modules\cii\models\UserLogoutContent' => 'Logout',
    		'app\modules\cii\models\UserRegisterContent' => 'Registration',
    		'app\modules\cii\models\UserActivateContent' => 'Activation',
    		'app\modules\cii\models\UserForgotContent' => 'Forgot',
    	];
    }

    public function getPermissionTypes() {
    	return Permission::getPermissions();
    }

    public function getSettingTypes() {
        return [
        	'name' => [
        		'label' => Yii::t('app', 'Website name'),
        		'type' => 'text',
        		'default' => 'My website'
        	],

        	'logo' => [
        		'label' => Yii::t('app', 'Website Logo'),
        		'type' => 'image'
        	],

        	'onlylogo' => [
        		'label' => Yii::t('app', 'Website only logo'),
        		'type' => 'boolean',
        		'default' => false
        	],

        	'rememberduration' => [
        		'label' => Yii::t('app', 'Remember login duration'),
        		'type' => 'integer',
        		'default' => 3600 * 24 * 30
        	],

        	'transport.type' => [
        		'label' => Yii::t('app', 'Mail Transport type'),
        		'type' => 'in',
        		'default' => 'file',
        		'values' => [
        			'file' => Yii::t('app', 'File'),
        			'sendmail' => Yii::t('app', 'Local mail system'),
        			'smtp' => Yii::t('app', 'SMTP'),
        		]
        	],

        	'transport.smtp.host' => [
        		'label' => Yii::t('app', 'SMTP Host'),
        		'type' => 'text'
        	],

        	'transport.smtp.user' => [
        		'label' => Yii::t('app', 'SMTP User'),
        		'type' => 'text'
        	],

        	'transport.smtp.password' => [
        		'label' => Yii::t('app', 'SMTP Password'),
        		'type' => 'password'
        	],

        	'transport.smtp.port' => [
        		'label' => Yii::t('app', 'SMTP Port'),
        		'type' => 'integer',
        		'default' => 465
        	],

        	'transport.smtp.encryption' => [
        		'label' => Yii::t('app', 'SMTP Encryption'),
        		'type' => 'in',
        		'values' => [
        			'ssl' => Yii::t('app', 'SSL'),
        			'tls' => Yii::t('app', 'TLS'),
        		],
        		'default' => 'ssl'
        	],

        	'sender' => [
        		'label' => Yii::t('app', 'Sending address'),
        		'type' => 'email',
        		'default' => 'my@website.local'
        	],
        ];
    }
}