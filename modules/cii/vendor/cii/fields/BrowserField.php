<?php
namespace cii\fields;

use Yii;
use cii\widgets\BrowserModal;
use cii\helpers\FileHelper;
use cii\helpers\Html;

abstract class BrowserField extends TextField {
    public function rules() {
        return [
            [['value'], 'string', 'max' => 255],
            [['value'], 'mimeType', 'types' => $this->getMimeTypes()],
        ];
    }

    abstract protected function getMimeTypes();

    public function mimeType($attribute, $params) {
        if(!is_null($this->getMimeTypes())) {
            $mimetype = FileHelper::getMimeTypes();
            $types = $this->getMimeTypes();
            if(!in_array($mimetype, $types)) {
                $this->addError($attribute, Yii::p('cii', 'Wrong mimetype for {attribute}. It should have the mimetype {mimetype}', [
                    'attribute' => $attribute,
                    'mimetype' => implode(', ', $types)
                ]));
            }
        }
    }

    public function getEditable($model, $form) {
        return BrowserModal::widget([
                'id' => $id = uniqid(),
                'mimeTypes' => $this->getMimeTypes(),
                'callbackSubmit' => '$("input[name=\"' . Html::getInputName($model, $this->attribute) . '\"]").val($("#' . $id . '_field").val())'
            ])
            . $form->field($model, $this->attribute, [
                'template' => "{label}\n"
                . "<div class=\"input-group\">"
                . "{input}"
                . "<span class=\"input-group-addon\">"
                . "<a class=\"glyphicon glyphicon glyphicon-file\" onclick=\"(function() {\$('#" . $id . "').modal('show')})()\"></a>"
                . "</span></div>\n{hint}\n{error}"
        ])->textInput([
            'maxlength' => true,    
        ]);
    }
}
