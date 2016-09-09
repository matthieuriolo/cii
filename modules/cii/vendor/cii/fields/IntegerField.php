<?php
namespace cii\fields;

use Yii;

class IntegerField extends TextField {
	public function getEditable($model, $form) {
        return $form->field($model, $this->attribute)->textInput(['maxlength' => true]);
    }
}
