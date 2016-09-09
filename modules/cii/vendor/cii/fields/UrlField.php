<?php
namespace cii\fields;

use Yii;

class UrlField extends TextField {
	public function getView($model) {
        return Yii::$app->formatter->asUrl($this->getRaw($model));
    }

	public function getEditable($model, $form) {
        return $form->field($model, $this->attribute)->textInput(['maxlength' => true]);
    }
}
