<?php

namespace app\modules\cii\models\route;

use yii\db\ActiveRecord;
use app\modules\cii\base\LazyRouteModel;
use app\modules\cii\models\common\Route;

class CaptchaRoute extends LazyRouteModel {
    public $allowChildren = false;
    static public $lazyControllerRoute = 'cii/route';

    
    public static function tableName() {
        return '{{%Cii_CaptchaRoute}}';
    }

    public function rules() {
        return [
            [['route_id'], 'required'],
            [['route_id', 'length_max', 'height', 'width', 'limit'], 'integer'],
            
            [['length_min'], 'integer', 'min' => 3],
            [['length_max'], 'integer', 'max' => 20],

            [['route_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['route_id' => 'id']],
            
            [['font_color'], 'string', 'max' => 26],
        ];
    }
    
    static public function getTypename() {
        return 'Cii:Captcha';
    }
   	
   	public function getRouteConfig() {
   		return [
        'class' => '\cii\web\routes\ControllerRoute',
        'baseRoute' => 'cii/site/captcha'
      ];
   	}
}
