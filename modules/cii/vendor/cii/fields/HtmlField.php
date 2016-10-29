<?php
namespace cii\fields;

use Yii;

class HtmlField extends TextareaField {
	public function getView($model) {
        //return Yii::$app->formatter->asHtml($this->getRaw($model));
        return $this->getRaw($model);
    }
}
