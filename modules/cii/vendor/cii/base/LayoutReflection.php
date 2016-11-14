<?php


namespace cii\base;

use Yii;
use app\modules\cii\models\extension\Layout;

class LayoutReflection extends BaseReflection {
    protected function getInstallationPath() {
        return Yii::getAlias(Yii::$app->layoutBasePath);
    }
    
    protected function getExtensionClassName() {
        return Layout::className();
    }

    public function getPositions() {
    	return isset($this->data['positions']) ? $this->data['positions'] : [];
    }

	public function getOverviewView() {
		return  isset($this->data['overview']) ? $this->data['overview'] : null;
	}
}
