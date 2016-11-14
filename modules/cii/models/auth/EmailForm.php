<?php


namespace app\modules\cii\models\auth;

use Yii;
use yii\captcha\Captcha;

class EmailForm extends User {
    public $email_field;
    public $email_repeat;
    public $captcha;

    public function afterSave($insert, $changedAttributes) {
        if(!Yii::$app->cii->mail(
            'app\modules\cii\mails\changedemail',
            $this->email,
            [
                'user' => $this,
            ]
        )) {
            throw new UserException('Could not sent password mail');
        }
        
        return parent::afterSave($insert, $changedAttributes);
    }

    public function beforeSave($insert) {
        $this->email = $this->email_field;
        return parent::beforeSave($insert);
    }

    /** @inheritdoc */
    public function rules() {
        return [
            [['email_field', 'email_repeat', 'captcha'], 'required'],
            [['captcha'], 'captcha', 'captchaAction' => 'cii/site/captcha', 'skipOnEmpty' => !Captcha::checkRequirements()],
            [['email_field', 'email_repeat'] , 'email'],

            [['email_field', 'email_repeat'], 'string', 'max' => 255],
            [['email_field'] , 'compare', 'compareAttribute' => 'email_repeat', 'message' => "Passwords don't match"],

            [['email_field'] , 'unique', 'targetAttribute' => 'email'],
        ];
    }
}
