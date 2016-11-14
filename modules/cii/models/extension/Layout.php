<?php

namespace app\modules\cii\models\extension;

use Yii;
use cii\behavior\ExtendableInterface;

class Layout extends BaseExtension {
    public static function tableName() {
        return '{{%Cii_Layout}}';
    }

    public function getReflection() {
        return Yii::$app->cii->layout->getReflection($this->name);
    }

    public function getSettings() {
	    return Yii::$app->cii->layout->getSettingTypes($this->extension->name);
	}

    public function getSettingTypes() {
        return $this->settings;
    }

    static public function getTypename() {
        return Yii::p('cii', 'Layout');
    }
}
