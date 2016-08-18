<?php

namespace app\modules\cii\mails;

use Yii;
use app\modules\cii\base\ViewMail;

class Forgot extends ViewMail {
    public $viewName = 'forgot';
    
    public function getSubject($data) {
        return Yii::t('app', 'Request for reseting password');
    }
}