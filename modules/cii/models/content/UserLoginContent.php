<?php

namespace app\modules\cii\models\content;

use Yii;
use cii\behavior\ExtendableInterface;
use app\modules\cii\base\LazyContentModel;
use app\modules\cii\base\ContentInterface;
use app\modules\cii\models\common\Route;

use yii\web\NotFoundHttpException;
use yii\base\InvalidConfigException;

class UserLoginContent extends LazyContentModel implements ContentInterface {
    public static $lazyControllerRoute = 'cii/user';
    public $canBeShadowed = true;
    
    public function init() {
        if(is_null($this->remember_visible)) {
            $this->remember_visible = true;
        }

        return parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%Cii_UserLoginContent}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['content_id'], 'required'],
            [['content_id', 'redirect_id', 'forgot_id', 'register_id'], 'integer'],
            
            [['content_id'], 'exist', 'skipOnError' => true, 'targetClass' => Content::className(), 'targetAttribute' => ['content_id' => 'id']],

            [['redirect_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['redirect_id' => 'id']],
            [['register_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['register_id' => 'id']],
            [['forgot_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['forgot_id' => 'id']],
            [['captcha_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['captcha_id' => 'id']],
            [['remember_visible'], 'boolean'],
        ];
    }


    public function getRedirect() {
        return $this->hasOne(Route::className(), ['id' => 'redirect_id']);
    }

    public function getForgot() {
        return $this->hasOne(Route::className(), ['id' => 'forgot_id']);
    }

    public function getRegister() {
        return $this->hasOne(Route::className(), ['id' => 'register_id']);
    }

    public function getCaptcha() {
        return $this->hasOne(Route::className(), ['id' => 'captcha_id']);
    }

    static public function getTypename() {
      return 'Cii:Login';
    }

    public function forwardToController($controller) {
        return Yii::$app->runAction('cii/site/login', Yii::$app->request->queryParams);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'redirect_id' => Yii::p('cii', 'Redirect'),
            'register_id' => Yii::p('cii', 'Register'),
            'forgot_id' => Yii::p('cii', 'Forgot'),
            'captcha_id' => Yii::p('cii', 'Captcha'),
            'remember_visible' => Yii::p('cii', 'Show remember button'),
        ];
    }

    public function getShadowInformation() {
        return [
            'route' => 'cii/site',
            'isVisible' => 'isVisibleInShadow',
            'action' => 'loginShadow',  
        ];
    }

    static public function getLazyCRUD() {
        if(empty(static::$lazyControllerRoute)) {
            throw new InvalidConfigException();
        }

        return [
            'controller' => Yii::$app->createController(static::$lazyControllerRoute)[0],
            'label' => 'getLazyLoginLabel',
            'view' => 'getLazyLoginView',
            'create' => 'getLazyLoginCreate',
            'update' => 'getLazyLoginUpdate',  
        ];
    }
}
