<?php
namespace cii\fields;

use Yii;

class IntegerField extends TextField {
	public function getView($model) {
		return Yii::$app->formatter->asInteger($this->getRaw($model));
	}

	public function getEditable($model, $form) {
        return $form->field($model, $this->attribute)->textInput(['maxlength' => true]);
    }

    public function getPreparedValue($model) {
        return intval($this->getRaw($model));
    }
}
