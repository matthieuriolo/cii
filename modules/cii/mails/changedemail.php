<?php

namespace app\modules\cii\mails;

use Yii;
use app\modules\cii\base\ViewMail;

class Changedemail extends ViewMail {
    public $viewName = 'changedemail';
    
    public function getSubject($data) {
        return Yii::t('app', 'You have changed your email');
    }
}