<?php
namespace cii\fields;

use Yii;

class EmailField extends TextField {
	public function getView($model) {
        return Yii::$app->formatter->asEmail($this->getRaw($model));
    }

	public function getEditable($model, $form) {
        return $form->field($model, $this->attribute)->emailInput(['maxlength' => true]);
    }
}
