<?php
namespace cii\fields;

use Yii;
use cii\widgets\BrowserModal;
use cii\helpers\FileHelper;
use cii\helpers\Html;
  			
class ImageField extends TextField {
	public function getEditable($model, $form) {
    	return BrowserModal::widget([
        		'id' => $id = uniqid(),
        		'mimeTypes' => FileHelper::$imageMimeTypes,
        		'callbackSubmit' => '$("input[name=\"' . Html::getInputName($model, $this->attribute) . '\"]").val($("#' . $id . '_field").val())'
        	])
        	. $form->field($model, $this->attribute, [
        		'template' => "{label}\n"
        		. "<div class=\"input-group\"><span class=\"input-group-addon\">"
        		. "<a class=\"glyphicon glyphicon glyphicon-file\" onclick=\"(function() {\$('#" . $id . "').modal('show')})()\"></a>"
        		. "</span>{input}</div>\n{hint}\n{error}"
        ])->textInput([
        	'maxlength' => true,	
        ]);
    }
}
