<?php

namespace app\modules\cii\models;

use Yii;
use cii\helpers\SPL;
use yii\base;

class Classname extends \yii\db\ActiveRecord {
    public static function tableName() {
        return '{{%Cii_Classname}}';
    }

    public function rules() {
        return [
            [['path'], 'required'],
            [['path'], 'string', 'max' => 255],
            [['path'], 'unique'],

            [['package_id'], 'exist', 'skipOnError' => true, 'targetClass' => Package::className(), 'targetAttribute' => ['package_id' => 'id']],
        ];
    }

    public function getPackage() {
        return $this->hasOne(Package::className(), ['id' => 'package_id']);
    }

    public function getTypename() {
        $class = $this->path;
        

        if(SPL::hasInterface($class, 'cii\behavior\ExtendableInterface')) {
            return $class::getTypename();
        }

        $path = explode('\\', $class);
        return end($path);
    }


    static function registerModel($path, $package = true) {
        if(is_object($path)) {
            $path = $path::className();
        }

        if($package === true) {
            if(strncmp($path, 'app\modules\\', 12) === 0 && strlen($path) > 12) {
                $tmp = explode('\\', $path);
                $package = $tmp[2];
            }
        }

        if(is_string($package)) {
            $package = Package::find()
                ->joinWith('extension as ext')
                ->where(['ext.name' => $package])
                ->one();
            if(!$package) {
                throw new InvalidConfigException();
            }
        }

        if(is_object($package)) {
            $package = $package->id;
        }

        if($ret = self::find()->where(['path' => $path])->one()) {
            return $ret->id;
        }

        $model = self::className();
        $model = new $model();
        $model->path = $path;
        $model->package_id = is_int($package) ? $package : null;
        $model->save();

        return $model->id;
    }
}
