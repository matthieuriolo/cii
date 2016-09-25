<?php
namespace cii\fields;

use Yii;

class PasswordField extends TextField {
	public function getView($model) {
		$txt = $this->getRaw($model);
        return empty($txt) ? null : Yii::$app->formatter->asText(str_pad('', strlen($txt) * 3, '●'));
    }

    public function getEditable($model, $form) {
        return $form->field($model, $this->attribute)->textInput([
        	'maxlength' => true,
        	'data-controller' => 'password-strength'
        ]);
    }
}
