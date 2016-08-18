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
class Layout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%Cii_Layout}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'enabled'], 'required'],
            [['enabled'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'enabled' => 'Enabled',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfigurations()
    {
        return $this->hasMany(Configuration::className(), ['layout_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['layout_id' => 'id']);
    }
}
