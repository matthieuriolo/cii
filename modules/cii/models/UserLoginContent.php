<?php

namespace app\modules\cii\models;

use Yii;
use cii\behavior\ExtendableInterface;
use app\modules\cii\base\LazyContentModel;
use app\modules\cii\base\ContentInterface;
use yii\web\NotFoundHttpException;
use yii\base\InvalidConfigException;

class UserLoginContent extends LazyContentModel implements ContentInterface {
    public static $lazyControllerRoute = 'cii/user';

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
            'id' => Yii::t('app', 'ID'),
            'redirect_id' => Yii::t('app', 'Redirect'),
            'register_id' => Yii::t('app', 'Register'),
            'forgot_id' => Yii::t('app', 'Forgot'),
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
