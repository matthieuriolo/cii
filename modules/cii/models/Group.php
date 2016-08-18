<?php

namespace app\modules\cii\models;

use Yii;

/**
 * This is the model class for table "{{%Cii_Group}}".
 *
 * @property integer $id
 * @property string $name
 *
 * @property CiiGroupMembers[] $ciiGroupMembers
 * @property CiiPermission[] $ciiPermissions
 */
class Group extends \yii\db\ActiveRecord {
    public static function tableName() {
        return '{{%Cii_Group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'enabled'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['enabled'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'create' => Yii::t('app', 'Create'),
            'enabled' => Yii::t('app', 'Enabled'),
        ];
    }

    public function getMembers() {
        return $this->hasMany(GroupMember::className(), ['group_id' => 'id']);
    }

    public function getPermissions() {
        return $this->hasMany(Permission::className(), ['group_id' => 'id']);
    }

    public function behaviors() {
        return [
            [
                'class' => 'cii\behavior\DatetimeBehavior',
                'create' => 'created'
            ]
        ];
    }
}
