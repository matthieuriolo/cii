<?php
namespace cii\fields;

use Yii;

class TextareaField extends AbstractField {
	
	public function getEditable($model, $form) {
        return $form->field($model, $this->attribute)->textarea(['rows' => 16, 'maxlength' => true]);
    }
}
