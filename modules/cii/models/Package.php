<?php

namespace app\modules\cii\models;

use Yii;

class Package extends BaseExtension {
    public static function tableName() {
        return '{{%Cii_Package}}';
    }
    
    public function getReflection() {
        return Yii::$app->cii->package->getReflection($this->name);
    }
    
    public function getSettings() {
	    return Yii::$app->cii->package->getSettingTypes($this->extension->name);
	}

    static public function getTypename() {
        return Yii::t('app', 'Package');
    }
}
