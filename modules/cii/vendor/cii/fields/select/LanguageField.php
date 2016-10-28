<?php
namespace cii\fields\select;

use Yii;
use cii\helpers\Url;
use cii\fields\PjaxField;

class LanguageField extends PjaxField {
	protected function getPjaxUrl() {
        return [
            Yii::$app->seo->relativeAdminRoute('modules/cii/language/index'),
            'pjaxid' => $this->pjaxid
        ];
    }
}
