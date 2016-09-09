<?php
namespace cii\fields;

use Yii;

class ColorField extends TextField {
	public function getView($model) {
		$val = $this->value;

		if($val) {
            return '<span class="color-block" style="background-color: ' . $val .';"></span> ' . Yii::$app->formatter->asText($val);
        }

        return null;
	}

	public function getEditable($model, $form) {
        return $form->field($model, $this->attribute)->textInput([
        	'maxlength' => true,
        	'data-controller' => 'colorpicker'
        ]);
    }
}
