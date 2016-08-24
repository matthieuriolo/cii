<?php

namespace app\modules\cii\models;

use Yii;
use app\modules\cii\models\Classname;

class Extension extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%Cii_Extension}}';
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
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'installed' => Yii::t('app', 'Installed'),
            'enabled' => Yii::t('app', 'Enabled'),
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
        $str = substr($this->classname->path, strlen('app\modules\cii\models') + 1);
        $str = strtolower($str);

        switch($str) {
            case 'package':
                return Yii::$app->cii->package->getReflection($this->name);
            case 'layout':
                return Yii::$app->cii->layout->getReflection($this->name);
        }

        return null;
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
