<?php
namespace cii\fields;

use Yii;

abstract class DropdownField extends AbstractField {
	public function getView($model) {
		$values = $this->getValues();
		$val = $this->getRaw($model);

		if(isset($values[$val])) {
        	return Yii::$app->formatter->asText($values[$val]);
        }

        return null;
    }

    public function getEditable($model, $form) {
        return $form->field($model, $this->attribute)->dropDownList($this->getValues());
    }

    abstract public function getValues();
}
