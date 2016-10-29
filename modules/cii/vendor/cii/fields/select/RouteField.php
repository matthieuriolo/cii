<?php
namespace cii\fields\select;

use Yii;
use cii\helpers\Url;
use cii\fields\PjaxObjectField;

use app\modules\cii\models\Route;

class RouteField extends PjaxObjectField {
	public function init() {
		$this->viewNameAttribute = 'slug';
		$this->header = Yii::p('cii', 'Select a route');
		parent::init();
	}

	protected function fetchModel($id) {
		return Route::findOne($id);
	}
	
	protected function getViewUrl($model, $data) {
		return [
			Yii::$app->seo->relativeAdminRoute('modules/cii/route/view'),
			'id' => $data->id,
		];
	}

	protected function getPjaxUrl() {
        return [
            Yii::$app->seo->relativeAdminRoute('modules/cii/route/index')
        ];
    }
}
