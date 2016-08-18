<?php

namespace app\modules\cii\models;

use Yii;
use cii\behavior\ExtendableInterface;
use app\modules\cii\base\LazyContentModel;
use app\modules\cii\base\ContentInterface;
use yii\web\NotFoundHttpException;

use yii\base\InvalidConfigException;

class UserLogoutContent extends LazyContentModel implements ContentInterface {
    public static $lazyControllerRoute = 'cii/user';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%Cii_UserLogoutContent}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['content_id'], 'required'],
            [['content_id', 'redirect_id'], 'integer'],
            
            [['content_id'], 'exist', 'skipOnError' => true, 'targetClass' => Content::className(), 'targetAttribute' => ['content_id' => 'id']],

            [['redirect_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['redirect_id' => 'id']],
        ];
    }

    public function getRedirect() {
        return $this->hasOne(Route::className(), ['id' => 'redirect_id']);
    }

    static public function getTypename() {
      return 'Cii:Logout';
    }

    public function forwardToController($controller) {
        return Yii::$app->runAction('cii/site/logout', Yii::$app->request->queryParams);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'redirect_id' => Yii::t('app', 'Redirect'),
        ];
    }


    static public function getLazyCRUD() {
        if(empty(static::$lazyControllerRoute)) {
            throw new InvalidConfigException();
        }

        return [
            'controller' => Yii::$app->createController(static::$lazyControllerRoute)[0],
            'label' => 'getLazyLogoutLabel',
            'view' => 'getLazyLogoutView',
            'create' => 'getLazyLogoutCreate',
            'update' => 'getLazyLogoutUpdate',  
        ];
    }
}
