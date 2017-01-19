<?php

namespace app\modules\cii\models\auth;

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
            [['enabled'], 'boolean'],

            [['countMembers'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'name' => Yii::p('cii', 'Name'),
            'create' => Yii::p('cii', 'Create'),
            'enabled' => Yii::p('cii', 'Enabled'),
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
            ],

            [
                'class' => 'cii\behavior\CounterTopBehavior',
                'counterMap' => [
                    'countMembers' => 'members'
                ]
            ]
        ];
    }
}
