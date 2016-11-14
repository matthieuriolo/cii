<?php

namespace app\modules\cii\models\route;

use Yii;

use app\modules\cii\base\LazyRouteModel;
use yii\web\NotFoundHttpException;
use yii\base\InvalidConfigException;
use app\modules\cii\models\common\Route;

class ProfileRoute extends LazyRouteModel {
    public static $lazyControllerRoute = 'cii/user';
    public $allowChildren = false;

    public static function tableName() {
        return '{{%Cii_ProfileRoute}}';
    }

    public function rules() {
        return [
            [['route_id'], 'required'],
            [['route_id', 'show_groups'], 'integer'],
            
            [['route_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['route_id' => 'id']],

            [['show_groups'], 'boolean'],
        ];
    }


    static public function getTypename() {
      return 'Cii:Profile';
    }

    public function attributeLabels() {
        return [
            'show_groups' => Yii::p('cii', 'Show groups'),
        ];
    }

    public function getRouteConfig() {
        return ['class' => 'app\modules\cii\routes\profile'];
    }

    /*
    
    
    public function forwardToController($controller) {
        return Yii::$app->runAction('cii/site/profile', Yii::$app->request->queryParams);
    }
    */
    static public function getLazyCRUD() {
        if(empty(static::$lazyControllerRoute)) {
            throw new InvalidConfigException();
        }

        return [
            'controller' => Yii::$app->createController(static::$lazyControllerRoute)[0],
            'label' => 'getLazyProfileLabel',
            'view' => 'getLazyProfileView',
            'create' => 'getLazyProfileCreate',
            'update' => 'getLazyProfileUpdate',  
        ];
    }
}
