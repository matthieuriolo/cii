<?php
namespace cii\fields\select;

use Yii;
use cii\helpers\Url;
use cii\fields\PjaxArrayField;
use app\modules\cii\models\Content;

class PositionTypesField extends PjaxArrayField {
	public $viewNameAttribute = 'position';

	public function init() {
		$this->header = Yii::p('cii', 'Select a layout position');
		parent::init();
	}


	protected function arrayContent() {
		return Yii::$app->cii->layout->getPositionsForDropdown();
	}

	protected function getPjaxUrl() {
        return [
            Yii::$app->seo->relativeAdminRoute('modules/cii/popup/position/index')
        ];
    }
}
