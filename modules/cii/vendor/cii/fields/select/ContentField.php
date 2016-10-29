<?php
namespace cii\fields\select;

use Yii;
use cii\helpers\Url;
use cii\fields\PjaxObjectField;
use app\modules\cii\models\Content;

class ContentField extends PjaxObjectField {
	public function init() {
		$this->header = Yii::p('cii', 'Select a content');
		parent::init();
	}

	protected function fetchModel($id) {
		return Content::findOne($id);
	}

	protected function getPjaxUrl() {
        return [
            Yii::$app->seo->relativeAdminRoute('modules/cii/content/index')
        ];
    }

    protected function getViewUrl($model, $data) {
		return [
			Yii::$app->seo->relativeAdminRoute('modules/cii/content/view'),
			'id' => $data->id,
		];
	}
}
