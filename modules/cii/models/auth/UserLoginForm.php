<?php


namespace app\modules\cii\models\auth;

use Yii;

class UserLoginForm extends LoginForm {
    protected function getCaptchaAction() {
        return 'cii/site/captcha';
    }
}
