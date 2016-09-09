<?php
namespace cii\fields\dropdown;

use Yii;
use cii\fields\DropdownField;

class PositionTypesField extends DropdownField {
	public function getValues() {
        return Yii::$app->cii->layout->getPositionsForDropdown();
    }
}
