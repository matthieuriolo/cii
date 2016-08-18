<?php


namespace app\modules\cii\models;

use Yii;
use yii\captcha\Captcha;

class DeleteForm extends User {
    public $password_field;
    public $captcha;

    public function afterSave($insert, $changedAttributes) {
        if(!Yii::$app->cii->mail(
            'app\modules\cii\mails\delete',
            $this->email,
            [
                'user' => $this,
            ]
        )) {
            throw new UserException('Delete user');
        }
        
        return parent::afterSave($insert, $changedAttributes);
    }

    public function validate_password($attribute, $params) {
        if(!$this->hasErrors()) {
            $user = Yii::$app->user->getIdentity();
            if(!$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password');
            }
        }
    }

    public function rules() {
        return [
            [['password_field', 'captcha'], 'required'],
            [['captcha'], 'captcha', 'captchaAction' => 'cii/site/captcha', 'skipOnEmpty' => !Captcha::checkRequirements()],
            [['passworld_field'], 'validate_password'],
            
        ];
    }
}
