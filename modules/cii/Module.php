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
    		'app\modules\cii\models\UserLoginContent' => Yii::p('cii', 'Login'),
    		'app\modules\cii\models\UserLogoutContent' => Yii::p('cii', 'Logout'),
    		'app\modules\cii\models\UserRegisterContent' => Yii::p('cii', 'Registration'),
    		'app\modules\cii\models\UserActivateContent' => Yii::p('cii', 'Activation'),
    		'app\modules\cii\models\UserForgotContent' => Yii::p('cii', 'Forgot'),
    	];
    }

    public function getFieldTypes() {
        return [
            'text' => 'cii\fields\TextField',
            'textarea' => 'cii\fields\TextareaField',
            'html' => 'cii\fields\HtmlField',
            'password' => 'cii\fields\PasswordField',
            'texteditor' => 'cii\fields\TexteditorField',
            'image' => 'cii\fields\ImageField',
            'captcha' => 'cii\fields\CaptchaField',
            

            'boolean' => 'cii\fields\BooleanField',
            
            'email' => 'cii\fields\EmailField',
            'color' => 'cii\fields\ColorField',
            'url' => 'cii\fields\UrlField',
            'integer' => 'cii\fields\IntegerField',
            'float' => 'cii\fields\FloatField',
            
            'datetime' => 'cii\fields\DatetimeField',
            'date' => 'cii\fields\DateField',
            'time' => 'cii\fields\TimeField',

            'in' => 'cii\fields\dropdown\InField',
            'language' => 'cii\fields\dropdown\ContentField',

            'extension' => 'cii\fields\dropdown\ExtensionField',
            
            'route' => 'cii\fields\dropdown\RouteField',
            'content' => 'cii\fields\dropdown\ContentField',
            
            'group' => 'cii\fields\dropdown\GroupField',
            'user' => 'cii\fields\dropdown\UserField',

            'fieldtypes' => 'cii\fields\dropdown\FieldTypesField',
            'positiontypes' => 'cii\fields\dropdown\PositionTypesField',
        ];
    }

    public function getPermissionTypes() {
    	return Permission::getPermissions();
    }
}