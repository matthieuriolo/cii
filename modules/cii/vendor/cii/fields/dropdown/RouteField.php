<?php
namespace cii\fields\dropdown;

use Yii;
use app\modules\cii\models\Route;

class RouteField extends RelationField {
    public $baseUri = 'cii/route/view';
    public $label_property = 'slug';

    public function findModel($pk) {
    	return Route::findOne($pk);
    }

	public function getValues() {
        return Yii::$app->cii->route->getRoutesForDropdown();
    }
}
