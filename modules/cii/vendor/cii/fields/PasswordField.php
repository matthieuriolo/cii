<?php
namespace cii\fields;

use Yii;

class PasswordField extends TextField {
	public function getView($model) {
        return Yii::$app->formatter->asText(str_pad('', strlen($this->getRaw($model)) * 3, '●'));
    }

    public function getEditable($model, $form) {
        return $form->field($model, $this->attribute)->textInput([
        	'maxlength' => true
        	'data-controller' => 'password-strength'
        ]);
    }
}
