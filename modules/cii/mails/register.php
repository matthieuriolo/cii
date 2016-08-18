<?php

namespace app\modules\cii\mails;

use Yii;
use app\modules\cii\base\ViewMail;

class Register extends ViewMail {
    public $viewName = 'register';
    
    public function getSubject($data) {
        return Yii::t('app', 'Sucessfully registered');
    }
}