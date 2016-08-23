<?php

namespace app\modules\cii\models;

use Yii;

/**
 * This is the model class for table "Core_Layout".
 *
 * @property integer $id
 * @property string $name
 * @property integer $enabled
 *
 * @property CoreConfiguration[] $coreConfigurations
 * @property CoreUser[] $coreUsers
 */
class Layout extends \yii\db\ActiveRecord {
    public static function tableName() {
        return '{{%Cii_Layout}}';
    }

    public function rules() {
        return [
            [['name', 'enabled'], 'required'],
            [['enabled'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'enabled' => 'Enabled',
        ];
    }

    public function getExtension() {
        return $this->owner->hasOne(Extension::className(), ['id' => 'extension_id'])->inverseOf('layout');
    }
    
    static public function getTypename() {
        return 'Cii:Layout';
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
