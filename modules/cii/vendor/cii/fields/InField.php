<?php
namespace cii\fields;

use Yii;
use cii\fields\DropdownField;

class InField extends DropdownField {
	public $values;

    public function getValues() {
        return $this->values;
    }
}
