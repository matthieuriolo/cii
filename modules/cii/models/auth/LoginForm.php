<?php


namespace app\modules\cii\models\auth;

use Yii;
use cii\base\Model;
use yii\captcha\Captcha;

abstract class LoginForm extends Model {
    
    public $email;
    public $password;
    //public $captcha;
    public $rememberMe = false;

    public $captchaRoute;
    public $captcha;
    protected $_user;
    
    abstract protected function getCaptchaAction();
    
    /** @inheritdoc */
    public function attributeLabels() {
        return [
            'email'      => Yii::p('cii', 'Email'),
            'password'   => Yii::p('cii', 'Password'),
            'rememberMe' => Yii::p('cii', 'Remember me next time'),
        ];
    }

    /** @inheritdoc */
    public function rules() {
        return [
            [['email', 'password'], 'required'],
            [['password'], 'validatePassword'],
            [['email'], 'activeUser'],
            [['rememberMe'], 'boolean'],

            [['captcha'], 'captcha', 'captchaAction' => $this->getCaptchaAction(), 'skipOnEmpty' => !Captcha::checkRequirements() || !$this->captchaRoute]
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if(!$this->hasErrors()) {
            $user = $this->getUser();
            if(!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password');
            }
        }
    }

    /**
     * Validates the user.
     * This method checks if the user is enable, in active or reset mode
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
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

    /**
     * Validates form and logs the user in.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login() {
        if($this->validate()) {
            $user = $this->getUser();
            $user->touch('last_login');
            $user->save();
            return Yii::$app->user->login($user, $this->rememberMe ? Yii::$app->cii->package->setting('cii', 'rememberduration') : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser() {
        if($this->_user === null) {
            $this->_user = User::findOne(['email' => $this->email]);
        }

        return $this->_user;
    }
}
