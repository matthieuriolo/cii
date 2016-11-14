<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\modules\cii\models\auth;

use Yii;
use yii\captcha\Captcha;
use yii\base\UserException;

class RegisterForm extends User {
    public $password_field;
    public $password_repeat;
    public $captcha;

    public function rules() {
        return [
            [['username', 'email', 'password_field', 'password_repeat'], 'required'],
            [['password_repeat'] , 'compare', 'compareAttribute' => 'password_field', 'message' => "Passwords don't match"],
            [['username', 'email'], 'string', 'max' => 255],

            [['email'], 'unique'],
            [['email'], 'email'],
            
            [['captcha'], 'captcha', 'captchaAction' => 'cii/site/captcha', 'skipOnEmpty' => !Captcha::checkRequirements()],
        ];
    }

    public function beforeSave($insert) {
        $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password_field);
        $this->token = Yii::$app->getSecurity()->generateRandomString(64);
        $this->enabled = true;
        return parent::beforeSave($insert);
    }


    public function afterSave($insert, $changedAttributes) {
        if(!Yii::$app->cii->mail(
            'app\modules\cii\mails\register',
            $this->email,
            [
                'user' => $this,
                'activate' => Yii::$app->seo->getModel()->content->outbox()->activate,
            ]
        )) {
            throw new UserException('Could not sent activation mail');
        }
        
        return parent::afterSave($insert, $changedAttributes);
    }

    public function attributeLabels() {
        return parent::attributeLabels() + [
            'password_repeat'   => Yii::p('cii', 'Verify Password'),
        ];
    }
}
