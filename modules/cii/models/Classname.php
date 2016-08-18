<?php

namespace app\modules\cii\models;

use Yii;
use cii\helpers\SPL;

class Classname extends \yii\db\ActiveRecord {
    public static function tableName() {
        return '{{%Cii_Classname}}';
    }

    public function rules() {
        return [
            [['path'], 'required'],
            [['path'], 'string', 'max' => 255],
            [['path'], 'unique'],
        ];
    }


    public function getTypename() {
        $class = $this->path;
        

        if(SPL::hasInterface($class, 'cii\behavior\ExtendableInterface')) {
            return $class::getTypename();
        }

        $path = explode('\\', $class);
        return end($path);
    }


    static function registerModel($path) {
        if(is_object($path)) {
            $path = $path::className();
        }

        if($ret = self::find()->where(['path' => $path])->one()) {
            return $ret->id;
        }

        $model = self::className();
        $model = new $model();
        $model->path = $path;
        $model->save();

        return $model->id;
    }
}
