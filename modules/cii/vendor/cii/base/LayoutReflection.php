<?php


namespace cii\base;

use Yii;
use app\modules\cii\models\Layout;

class LayoutReflection extends BaseReflection {
    protected function getInstallationPath() {
        return Yii::$app->layoutBasePath;
    }
    
    protected function getExtensionClassName() {
        return Layout::className();
    }
}
