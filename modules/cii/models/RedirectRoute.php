<?php

namespace app\modules\cii\models;

use Yii;
use yii\db\ActiveRecord;
use app\modules\cii\base\LazyRouteModel;

class RedirectRoute extends LazyRouteModel {
    public static $lazyControllerRoute = 'cii/content';
    public $allowChildren = false;

    public static function tableName() {
        return '{{%Cii_RedirectRoute}}';
    }

    public function rules() {
        return [
            [['type'], 'required'],
            [['route_id', 'redirect_id', 'type'], 'integer'],
            [['route_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['route_id' => 'id']],
            [['redirect_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['redirect_id' => 'id']],
            [['type'], 'in', 'range' => array_keys($this->getTypes())],
            [['url'], 'string', 'max' => 255],
            [['url'], 'url']
        ];
    }

    public function attributeLabels() {
        return [
            'redirect_id' => Yii::t('app', 'Internal redirect'),
            'type' => Yii::t('app', 'Type'),
            'url' => Yii::t('app', 'External redirect')
        ];
    }

    public function getRedirect() {
        return $this->hasOne(Route::className(), ['id' => 'redirect_id']);
    }

    public function getTypes() {
      return [
        302 => Yii::t('app', 'Temporary'),
        301 => Yii::t('app', 'Permanent'),
      ];
    }

    static public function getTypename() {
        return 'Cii:Redirect';
    }

   	public function getRouteConfig() {
   		return [
        'class' => '\cii\web\routes\ControllerRoute',
        'baseRoute' => 'cii/site/redirect'
      ];
   	}

    static public function getLazyCRUD() {
        return [
            'controller' => Yii::$app->createController(static::$lazyControllerRoute)[0],
            'label' => 'getLazyRedirectLabel',
            'view' => 'getLazyRedirectView',
            'create' => 'getLazyRedirectCreate',
            'update' => 'getLazyRedirectUpdate',  
        ];
    }
}
