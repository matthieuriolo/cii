<?php 

namespace app\modules\cii;
use Yii;


class Module extends \cii\backend\Package {
    public function getBackendItems() {
    	return Menu::getBackendItems($this);
    }

    public function getRouteTypes() {
    	return [
            'app\modules\cii\models\ContentRoute' => Yii::p('cii', 'Content'),
    		'app\modules\cii\models\RedirectRoute' => Yii::p('cii', 'Redirect'),
    		'app\modules\cii\models\CaptchaRoute' => Yii::p('cii', 'Captcha'),
            'app\modules\cii\models\ProfileRoute' => Yii::p('cii', 'Profile'),
            'app\modules\cii\models\BackendRoute' => Yii::p('cii', 'Administrator'),
    		'app\modules\cii\models\GiiRoute' => Yii::p('cii', 'Gii generator'),
    		'app\modules\cii\models\DocRoute' => Yii::p('cii', 'Documentation'),
    	];
    }

    public function getContentTypes() {
    	return [
    		'app\modules\cii\models\CustomContent' => Yii::p('cii', 'CustomContent'),
            'app\modules\cii\models\UserLoginContent' => Yii::p('cii', 'Login'),
    		'app\modules\cii\models\UserLogoutContent' => Yii::p('cii', 'Logout'),
    		'app\modules\cii\models\UserRegisterContent' => Yii::p('cii', 'Registration'),
    		'app\modules\cii\models\UserActivateContent' => Yii::p('cii', 'Activation'),
    		'app\modules\cii\models\UserForgotContent' => Yii::p('cii', 'Forgot'),
    	];
    }

    public function getPermissionTypes() {
    	return Permission::getPermissions();
    }
}