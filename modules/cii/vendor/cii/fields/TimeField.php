<?php
namespace cii\fields;

use Yii;

class TimeField extends TextField {
	public function getView($model) {
		return Yii::$app->formatter->asTime($this->getRaw($model));
	}

	public function getEditable($model, $form) {
        return $form->field($model, $this->attribute)->textInput([
        	'maxlength' => true,
        	'data-controller' => 'datetimepicker',
        	'options' => ['format' => 'LT']
        ]);
    }
}
