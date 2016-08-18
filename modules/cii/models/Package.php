<?php

namespace app\modules\cii\models;

use Yii;
use yii\db\ActiveRecord;
use cii\behavior\ExtendableInterface;

class Package extends ActiveRecord implements ExtendableInterface {
	
    public static function tableName() {
        return '{{%Cii_Package}}';
    }

    public function getExtension() {
        return $this->owner->hasOne(Extension::className(), ['id' => 'extension_id'])->inverseOf('package');
    }


    public function getReflection() {
        return Yii::$app->cii->package->getReflection($this->name);
    }

    static public function canOutboxFrom($class) {
        if($class instanceof Extension) {
          return true;
        }

        return false;
    }

    static public function getTypename() {
      return 'Cii:Package';
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
