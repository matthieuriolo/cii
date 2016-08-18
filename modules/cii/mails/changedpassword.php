<?php

namespace app\modules\cii\mails;

use Yii;
use app\modules\cii\base\ViewMail;

class Changedpassword extends ViewMail {
    public $viewName = 'changedpassword';
    
    public function getSubject($data) {
        return Yii::t('app', 'Your password have been changed');
    }
}