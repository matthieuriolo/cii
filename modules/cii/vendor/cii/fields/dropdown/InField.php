<?php
namespace cii\fields\dropdown;

use Yii;
use cii\fields\DropdownField;

class InField extends DropdownField {
	public $values;

    public function getValues() {
        return $this->values;
    }
}
