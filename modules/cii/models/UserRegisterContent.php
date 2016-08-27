<?php

namespace app\modules\cii\models;

use Yii;
use cii\behavior\ExtendableInterface;
use app\modules\cii\base\LazyContentModel;
use app\modules\cii\base\ContentInterface;
use yii\web\NotFoundHttpException;
use yii\base\InvalidConfigException;

class UserRegisterContent extends LazyContentModel implements ContentInterface {
    public static $lazyControllerRoute = 'cii/user';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%Cii_UserRegisterContent}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content_id', 'activate_id'], 'required'],
            [['content_id', 'redirect_id', 'forgot_id', 'login_id', 'activate_id'], 'integer'],
            
            [['content_id'], 'exist', 'skipOnError' => true, 'targetClass' => Content::className(), 'targetAttribute' => ['content_id' => 'id']],

            [['activate_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['activate_id' => 'id']],
            [['redirect_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['redirect_id' => 'id']],
            [['login_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['login_id' => 'id']],
            [['forgot_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['forgot_id' => 'id']],
        ];
    }


    public function getRedirect() {
        return $this->hasOne(Route::className(), ['id' => 'redirect_id']);
    }

    public function getForgot() {
        return $this->hasOne(Route::className(), ['id' => 'forgot_id']);
    }

    public function getLogin() {
        return $this->hasOne(Route::className(), ['id' => 'login_id']);
    }

    public function getActivate() {
        return $this->hasOne(Route::className(), ['id' => 'activate_id']);
    }


    static public function getTypename() {
      return 'Cii:Register';
    }

    public function forwardToController($controller) {
        return Yii::$app->runAction('cii/site/register', Yii::$app->request->queryParams);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'redirect_id' => Yii::p('cii', 'Redirect'),
            'login_id' => Yii::p('cii', 'Login'),
            'forgot_id' => Yii::p('cii', 'Forgot'),
            'activate_id' => Yii::p('cii', 'Activate'),
        ];
    }


    static public function getLazyCRUD() {
        if(empty(static::$lazyControllerRoute)) {
            throw new InvalidConfigException();
        }

        return [
            'controller' => Yii::$app->createController(static::$lazyControllerRoute)[0],
            'label' => 'getLazyRegisterLabel',
            'view' => 'getLazyRegisterView',
            'create' => 'getLazyRegisterCreate',
            'update' => 'getLazyRegisterUpdate',  
        ];
    }
}
