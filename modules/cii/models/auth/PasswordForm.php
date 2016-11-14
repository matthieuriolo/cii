<?php


namespace app\modules\cii\models\auth;

use Yii;
use yii\captcha\Captcha;

class PasswordForm extends User {
    public $password_field;
    public $password_repeat;

    public $captcha;

    protected $_user;

    /** @inheritdoc */
    public function attributeLabels() {
        return [
            'password' => Yii::p('cii', 'Password'),
            'password_repeat' => Yii::p('cii', 'Verify Password'),
        ];
    }

    public function beforeSave($insert) {
        $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password_field);
        return parent::beforeSave($insert);
    }


    public function afterSave($insert, $changedAttributes) {
        if(!Yii::$app->cii->mail(
            'app\modules\cii\mails\changedpassword',
            $this->email,
            [
                'user' => $this,
            ]
        )) {
            throw new UserException('Could not sent password mail');
        }
        
        return parent::afterSave($insert, $changedAttributes);
    }


    /** @inheritdoc */
    public function rules() {
        return [
            [['password_field', 'password_repeat', 'captcha'], 'required'],
            [['captcha'], 'captcha', 'captchaAction' => 'cii/site/captcha', 'skipOnEmpty' => !Captcha::checkRequirements()],
            [['password_repeat'] , 'compare', 'compareAttribute' => 'password_field', 'message' => "Passwords don't match"],
        ];
    }
}
