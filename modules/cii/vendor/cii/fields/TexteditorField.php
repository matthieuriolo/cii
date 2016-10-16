<?php
namespace cii\fields;

use Yii;
use cii\widgets\BrowserModal;
use cii\widgets\EditView;

class TexteditorField extends TextareaField {
	public function getEditable($model, $form) {
		return $form->field($model, $this->attribute)->textarea([
        	'rows' => 25,
        	'data-controller' => 'tinymce'
        ]);
    }
}
