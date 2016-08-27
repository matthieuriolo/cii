<?php

namespace app\modules\cii\models;

use Yii;

/**
 * This is the model class for table "{{%Cii_GroupMembers}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $group_id
 *
 * @property CiiGroup $group
 * @property CiiUser $user
 */
class GroupMember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%Cii_GroupMembers}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id', 'group_id'], 'required'],
            [['user_id', 'group_id'], 'integer'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['group_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'user_id' => Yii::p('cii', 'User'),
            'group_id' => Yii::p('cii', 'Group'),
            'created' => Yii::p('cii', 'Member since'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup() {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
