<?php

namespace app\modules\cii\models;

use Yii;
use cii\behavior\ExtendableInterface;
use app\modules\cii\base\LazyContentModel;
use app\modules\cii\base\ContentInterface;
use yii\web\NotFoundHttpException;

use yii\base\InvalidConfigException;

class UserActivateContent extends LazyContentModel implements ContentInterface {
    public static $lazyControllerRoute = 'cii/user';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%Cii_UserActivateContent}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['content_id'], 'required'],
            [['content_id', 'redirect_id', 'register_id', 'login_id'], 'integer'],
            
            [['content_id'], 'exist', 'skipOnError' => true, 'targetClass' => Content::className(), 'targetAttribute' => ['content_id' => 'id']],

            [['redirect_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['redirect_id' => 'id']],
            [['login_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['login_id' => 'id']],
            [['register_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['register_id' => 'id']],
        ];
    }

    public function getRedirect() {
        return $this->hasOne(Route::className(), ['id' => 'redirect_id']);
    }

    public function getRegister() {
        return $this->hasOne(Route::className(), ['id' => 'register_id']);
    }

    public function getLogin() {
        return $this->hasOne(Route::className(), ['id' => 'login_id']);
    }

    

    static public function getTypename() {
      return 'Cii:Activate';
    }

    public function forwardToController($controller) {
        return Yii::$app->runAction('cii/site/activate', Yii::$app->request->queryParams);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'redirect_id' => Yii::t('app', 'Redirect'),
            'login_id' => Yii::t('app', 'Login'),
            'register_id' => Yii::t('app', 'Register'),
        ];
    }


    static public function getLazyCRUD() {
        if(empty(static::$lazyControllerRoute)) {
            throw new InvalidConfigException();
        }

        return [
            'controller' => Yii::$app->createController(static::$lazyControllerRoute)[0],
            'label' => 'getLazyActivateLabel',
            'view' => 'getLazyActivateView',
            'create' => 'getLazyActivateCreate',
            'update' => 'getLazyActivateUpdate',  
        ];
    }
}
