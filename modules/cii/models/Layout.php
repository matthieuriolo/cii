<?php

namespace app\modules\cii\models;

use Yii;
use cii\behavior\ExtendableInterface;

class Layout extends BaseExtension {
    public static function tableName() {
        return '{{%Cii_Layout}}';
    }

    public function getReflection() {
        return Yii::$app->cii->layout->getReflection($this->name);
    }

    static public function getTypename() {
        return Yii::t('app', 'Layout');
    }
}
