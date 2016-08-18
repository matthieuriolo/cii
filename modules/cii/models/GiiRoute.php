<?php

namespace app\modules\cii\models;

use yii\db\ActiveRecord;
use app\modules\cii\base\LazyRouteModel;

class GiiRoute extends LazyRouteModel {
    public $allowChildren = false;
    
    public static function tableName() {
        return '{{%Cii_GiiRoute}}';
    }

    public function rules() {
        return [
            [['route_id'], 'integer'],
            [['route_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['route_id' => 'id']],
        ];
    }
    
    static public function getTypename() {
      return 'Cii:Gii';
    }
   	
   	public function getRouteConfig() {
   		return ['class' => 'app\modules\cii\routes\gii'];
   	}
}
