<?php

namespace app\modules\cii\models;

use Yii;

use yii\web\IdentityInterface;
use \yii\db\ActiveRecord;
use yii\base\NotSupportedException;

class UpdateForm extends User {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%Cii_User}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['username'], 'required'],
            [['language_id', 'layout_id'], 'integer'],
            [['enabled'], 'boolean'],
            [['username', 'email'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['email'], 'email'],
            
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['layout_id'], 'exist', 'skipOnError' => true, 'targetClass' => Layout::className(), 'targetAttribute' => ['layout_id' => 'id']],
        ];
    }
}
