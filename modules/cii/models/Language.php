<?php

namespace app\modules\cii\models;

use Yii;

/**
 * This is the model class for table "Core_Language".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $shortCode
 *
 * @property CoreContentVisibilities[] $coreContentVisibilities
 * @property CoreRoute[] $coreRoutes
 * @property CoreUser[] $coreUsers
 */
class Language extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%Cii_Language}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'code', 'enabled'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['shortcode'], 'string', 'max' => 2],
            [['code'], 'string', 'max' => 6],
            [['code'], 'unique'],
            [['enabled'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            'shortCode' => 'Short Code',
            'enabled' => 'Enabled',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentVisibilities() {
        return $this->hasMany(ContentVisibilities::className(), ['language_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes() {
        return $this->hasMany(Route::className(), ['language_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers() {
        return $this->hasMany(User::className(), ['language_id' => 'id']);
    }
}
