<?php

namespace app\modules\cii\models\route;

use Yii;
use yii\db\ActiveRecord;
use app\modules\cii\base\LazyRouteModel;
use app\modules\cii\models\common\Route;

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
            'redirect_id' => Yii::p('cii', 'Internal redirect'),
            'type' => Yii::p('cii', 'Type'),
            'url' => Yii::p('cii', 'External redirect')
        ];
    }

    public function getRedirect() {
        return $this->hasOne(Route::className(), ['id' => 'redirect_id']);
    }

    public function getTypes() {
      return [
        302 => Yii::p('cii', 'Temporary'),
        301 => Yii::p('cii', 'Permanent'),
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
