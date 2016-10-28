<?php
namespace cii\fields\select;

use Yii;
use cii\helpers\Url;
use cii\fields\PjaxField;

class RouteField extends PjaxField {
	protected function getPjaxUrl() {
        return [
            Yii::$app->seo->relativeAdminRoute('modules/cii/route/index'),
            'pjaxid' => $this->pjaxid
        ];
    }
}
