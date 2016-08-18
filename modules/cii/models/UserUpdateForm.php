<?php

namespace app\modules\cii\models;

use Yii;
use yii\base\Security;

class UserUpdateForm extends User {
    public function rules() {
        return [
            [['username', 'email', 'enabled'], 'required'],
            [['username', 'email'], 'string', 'max' => 255],
            [['enabled'], 'boolean'],
            
            [['email'], 'unique'],
            
            [['language_id', 'layout_id'], 'integer'],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['layout_id'], 'exist', 'skipOnError' => true, 'targetClass' => Layout::className(), 'targetAttribute' => ['layout_id' => 'id']],
        ];
    }
}
