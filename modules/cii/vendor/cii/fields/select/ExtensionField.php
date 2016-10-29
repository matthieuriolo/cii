<?php
namespace cii\fields\select;

use Yii;
use cii\helpers\Url;
use cii\fields\PjaxObjectField;

use app\modules\cii\models\Extension;

class ExtensionField extends PjaxObjectField {
	public function init() {
		$this->header = Yii::p('cii', 'Select an extension');
		parent::init();
	}

	protected function fetchModel($id) {
		return Extension::findOne($id);
	}

	protected function getPjaxUrl() {
        return [
            Yii::$app->seo->relativeAdminRoute('modules/cii/extension/index')
        ];
    }

    protected function getViewUrl($model, $data) {
    	return $data ? [
			Yii::$app->seo->relativeAdminRoute('modules/cii/extension/view'),
			'id' => $data->id,
		] : null;
	}
}
