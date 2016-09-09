<?php
namespace cii\fields\dropdown;

use Yii;

class RouteField extends RelationField {
    public $baseUri = 'cii/route/view';
    public $label_property = 'slug';

	public function getValues() {
        return Yii::$app->cii->route->getRoutesForDropdown();
    }
}
