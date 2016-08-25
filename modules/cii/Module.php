<?php 

namespace app\modules\cii;
use Yii;


class Module extends \cii\backend\Package {
    public function getBackendItems() {
    	return Menu::getBackendItems($this);
    }

    public function getRouteTypes() {
    	return [
    		'app\modules\cii\models\ContentRoute' => 'Content',
    		'app\modules\cii\models\ProfileRoute' => 'Profile',
            'app\modules\cii\models\RedirectRoute' => 'Redirect',

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
}