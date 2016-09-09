<?php
namespace cii\fields;

use Yii;

class TextField extends AbstractField {
	public function getEditable($model, $form) {
        return $form->field($model, $this->attribute)->textInput(['maxlength' => true]);
    }
}
