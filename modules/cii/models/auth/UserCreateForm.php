<?php

namespace app\modules\cii\models\auth;

use Yii;
use yii\base\Security;

class UserCreateForm extends User {
    public $password_field;
    public $password_repeat;
    
    public function rules() {
        return [
            [['username', 'email', 'enabled', 'password_field', 'password_repeat'], 'required'],
            [['username', 'email', 'password_field', 'password_repeat'], 'string', 'max' => 255],
            [['enabled'], 'boolean'],
            
            [['password_repeat'] , 'compare', 'compareAttribute' => 'password_field', 'message' => "Passwords don't match"],

            [['email'], 'unique'],
            [['email'], 'email'],
            
            [['language_id', 'layout_id'], 'integer'],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['layout_id'], 'exist', 'skipOnError' => true, 'targetClass' => Layout::className(), 'targetAttribute' => ['layout_id' => 'id']],
        ];
    }


    public function beforeSave($insert) {
        $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password_field);
        return parent::beforeSave($insert);
    }

    
    public function behaviors() {
        return [
            [
                'class' => 'cii\behavior\DatetimeBehavior',
                'create' => ['activated', 'created']
            ]
        ];
    }
}
