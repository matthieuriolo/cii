<?php

namespace app\modules\cii\models;

use Yii;
use app\modules\cii\models\Classname;

class Extension extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%Cii_Abstract_Extension}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'installed', 'enabled'], 'required'],
            [['installed'], 'safe'],
            [['enabled'], 'boolean'],
            [['name'], 'string', 'max' => 45],
            [['classname_id'], 'integer'],
            [['classname_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classname::className(), 'targetAttribute' => ['classname_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'name' => Yii::p('cii', 'Name'),
            'installed' => Yii::p('cii', 'Installed'),
            'enabled' => Yii::p('cii', 'Enabled'),
            'type' => Yii::p('cii', 'Type'),
        ];
    }

    public function getConfigurations() {
        return $this->hasMany(Configuration::className(), ['extension_id' => 'id']);
    }

    public function getPackage() {
        return $this->hasOne(Package::className(), ['extension_id' => 'id'])->inverseOf('extension');
    }

    public function getLayout() {
        return $this->hasOne(Layout::className(), ['extension_id' => 'id'])->inverseOf('extension');
    }
    

    public function getReflection() {
        return $this->outbox()->getReflection();
    }

    public function getSettings() {
        return $this->outbox()->settings;
    }


    public function getType() {
        $model = $this->outbox()->className();
        return $model::getTypename();
    }

    public function behaviors() {
        return [
            [
                'class' => 'cii\behavior\ExtendableBehavior',
                'throwErrorUnboxed' => true,
            ],

            [
                'class' => 'cii\behavior\DatetimeBehavior',
                'create' => 'installed'
            ]
        ];
    }
}
