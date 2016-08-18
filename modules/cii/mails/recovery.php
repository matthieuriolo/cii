<?php

namespace app\modules\cii\mails;

use Yii;
use app\modules\cii\base\ViewMail;

class Recovery extends ViewMail {
    public $viewName = 'recovery';
    
    public function getSubject($data) {
        return Yii::t('app', 'Your password has been reset');
    }
}