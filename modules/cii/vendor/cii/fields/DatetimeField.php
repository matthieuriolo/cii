<?php
namespace cii\fields;

use Yii;

class DatetimeField extends TextField {
	public function getView($model) {
		return Yii::$app->formatter->asDatetime($this->getRaw($model));
	}

	public function getEditable($model, $form) {
        return $form->field($model, $this->attribute)->textInput([
        	'maxlength' => true,
        	'data-controller' => 'datetimepicker'
        ]);
    }
}
