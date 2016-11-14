<?php
namespace cii\fields\select;

use Yii;
use cii\helpers\Url;
use cii\fields\PjaxObjectField;

use app\modules\cii\models\extension\Language;

class LanguageField extends PjaxObjectField {
	protected function fetchModel($id) {
		return Language::findOne($id);
	}

	protected function getPjaxUrl() {
        return [
            Yii::$app->seo->relativeAdminRoute('modules/cii/language/index'),
            'pjaxid' => $this->pjaxid
        ];
    }
}
