<?php
namespace cii\fields\dropdown;

use Yii;
use app\modules\cii\models\Route;

class RouteField extends RelationField {
    public $baseUri = 'cii/route/view';
    public $label_property = 'slug';

    public $allowChildren = true;

    public function findModel($pk) {
    	return Route::findOne($pk);
    }

	public function getValues() {
		if(!$this->allowChildren) {
			return Yii::$app->cii->route->getParentRoutesForDropdown();
		}

        return Yii::$app->cii->route->getRoutesForDropdown();
    }
}
