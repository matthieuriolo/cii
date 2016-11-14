<?php

namespace app\modules\cii\models\common;

use Yii;
use cii\helpers\SPL;

class CountAccess extends \yii\db\ActiveRecord {
    public function init() {
        $this->hits = 0;
    }
    
    public static function tableName() {
        return '{{%Cii_CountAccess}}';
    }

    public function rules() {
        return [
            [['hits', 'route_id', 'created'], 'required'],
            [['created', 'route_id'], 'unique', 'targetAttribute' => ['created', 'route_id']],
            [['hits', 'route_id'], 'integer'],

            [['route_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['route_id' => 'id']],
        ];
    }
}
