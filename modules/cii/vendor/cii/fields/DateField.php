<?php
namespace cii\fields;

use Yii;

class DateField extends TextField {
	public function getView($model) {
		return Yii::$app->formatter->asDate($this->getRaw($model));
	}

	public function getEditable($model, $form) {
        return $form->field($model, $this->attribute)->textInput([
        	'maxlength' => true,
        	'data-controller' => 'datetimepicker',
        	'options' => ['format' => 'LT']
        ]);
    }
}
