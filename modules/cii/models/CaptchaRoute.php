<?php

namespace app\modules\cii\models;

use yii\db\ActiveRecord;
use app\modules\cii\base\LazyRouteModel;

class CaptchaRoute extends LazyRouteModel {
    public $allowChildren = false;
    
    public static function tableName() {
        return '{{%Cii_CaptchaRoute}}';
    }

    public function rules() {
        return [
            [['route_id'], 'integer'],
            [['route_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['route_id' => 'id']],
        ];
    }
    
    static public function getTypename() {
      return 'Cii:Captcha';
    }
   	
   	public function getRouteConfig() {
   		return [
        'class' => '\cii\web\routes\ControllerRoute',
        'baseRoute' => 'site/captcha'
      ];
   	}
}
