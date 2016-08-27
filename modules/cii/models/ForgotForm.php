<?php


namespace app\modules\cii\models;

use Yii;
use yii\base\Model;
use yii\captcha\Captcha;
use cii\web\SecurityException;

class ForgotForm extends Model {
    
    public $email;
    public $captcha;

    protected $_user;

    /** @inheritdoc */
    public function attributeLabels() {
        return [
            'email'      => Yii::p('cii', 'Email'),
        ];
    }

    /** @inheritdoc */
    public function rules() {
        return [
            [['email', 'captcha'], 'required'],
            [['email'], 'activeUser'],
            [['captcha'], 'captcha', 'captchaAction' => 'cii/site/captcha', 'skipOnEmpty' => !Captcha::checkRequirements()],
        ];
    }

    public function activeUser($attribute, $params) {
        if(!$this->hasErrors()) {
            if($user = $this->getUser()) {
                if(!$user->enabled) {
                    $this->addError($attribute, 'This user is not active');
                }else if(!empty($user->token)) {
                    $this->addError($attribute, 'This user has not been activated yet');
                }
            }else {
                $this->addError($attribute, 'No user found with this email');
            }
        }
    }

    public function forgot() {
        $user = $this->getUser();
        if($user->superadmin) {
            throw new SecurityException();
        }

        $user->token = Yii::$app->getSecurity()->generateRandomString(64);

        if(!$user->save() || !Yii::$app->cii->mail(
            'app\modules\cii\mails\forgot',
            $user->email,
            [
                'user' => $user,
                'forgot' => Yii::$app->seo->getModel()->route,
            ]
        )) {
            throw new UserException('Could not sent recovery mail');
        }

        return true;
    }

    protected function getUser() {
        if($this->_user === null) {
            $this->_user = User::findOne(['email' => $this->email]);
        }

        return $this->_user;
    }
}
