<?php

namespace app\modules\cii\models;

use Yii;

class Permission extends \yii\db\ActiveRecord {
    
    public static function tableName() {
        return '{{%Cii_Permission}}';
    }

    public function rules() {
        return [
            [['permission_id', 'group_id', 'package_id'], 'required'],
            [['group_id', 'package_id', 'permission_id'], 'integer'],
            
            [['package_id'], 'exist', 'skipOnError' => true, 'targetClass' => Package::className(), 'targetAttribute' => ['package_id' => 'id']],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['group_id' => 'id']],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'permission_id' => Yii::t('app', 'Name'),
            'group_id' => Yii::t('app', 'Group'),
            'package_id' => Yii::t('app', 'Module'),
        ];
    }

    public function getPackage() {
        return $this->hasOne(Package::className(), ['id' => 'package_id']);
    }

    public function getGroup() {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }

    public function getName() {
        $vals = Yii::$app->getModule($this->package->name)->getPermissionTypes();
        return $vals[$this->permission_id];
    }
}
