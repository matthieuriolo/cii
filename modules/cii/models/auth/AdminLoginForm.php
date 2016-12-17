<?php


namespace app\modules\cii\models\auth;

use Yii;

class AdminLoginForm extends LoginForm {
    protected function getCaptchaAction() {
        return 'cii/backend/captcha';
    }
}
