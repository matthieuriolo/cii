<?php
namespace cii\fields\select;

use Yii;
use cii\helpers\Url;
use cii\fields\PjaxField;

class ContentField extends PjaxField {
	protected function getPjaxUrl() {
        return [
            Yii::$app->seo->relativeAdminRoute('modules/cii/content/index'),
            'pjaxid' => $this->pjaxid
        ];
    }
}
