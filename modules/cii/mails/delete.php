<?php

namespace app\modules\cii\mails;

use Yii;
use app\modules\cii\base\ViewMail;

class Delete extends ViewMail {
    public $viewName = 'delete';
    
    public function getSubject($data) {
        return Yii::t('app', 'Your account have been deleted');
    }
}