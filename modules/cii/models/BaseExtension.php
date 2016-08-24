<?php

namespace app\modules\cii\models;

use Yii;
use yii\db\ActiveRecord;
use cii\behavior\ExtendableInterface;

abstract class BaseExtension extends ActiveRecord implements ExtendableInterface {
    public function getExtension() {
        return $this->owner->hasOne(Extension::className(), ['id' => 'extension_id']);
    }
    
    abstract public function getReflection();
    abstract public function getSettings();


    static public function canOutboxFrom($class) {
        if($class instanceof Extension) {
          return true;
        }

        return false;
    }

    static public function getOutboxAttribute($class) {
        return 'extension_id';
    }

    public function behaviors() {
        return [
            [
                'class' => 'cii\behavior\InheritableBehavior',
                'inheritProperties' => [
                    'name' => 'extension.name',
                    'enabled' => 'extension.enabled',
                    'installed' => 'extension.installed'
                ]
            ]
        ];
    }
}
