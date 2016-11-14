<?php

namespace app\modules\cii\models\route;

use app\modules\cii\models\common\Route;
use app\modules\cii\base\LazyRouteModel;

class BackendRoute extends LazyRouteModel {
    public $allowChildren = false;

    public static function tableName() {
        return '{{%Cii_BackendRoute}}';
    }

    public function rules() {
        return [
            [['route_id'], 'integer'],
            [['route_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['route_id' => 'id']],
        ];
    }

    static public function getTypename() {
      return 'Cii:Backend';
    }
    
   	public function getRouteConfig() {
   		return ['class' => 'app\modules\cii\routes\backend'];
   	}
}
