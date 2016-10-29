<?php
namespace cii\fields;

use Yii;
use cii\widgets\PjaxModal;
use cii\helpers\Url;
use cii\helpers\Html;

abstract class PjaxArrayField extends PjaxField {
    public $viewNameAttribute = 'name';

    abstract protected function arrayContent();

    public function getView($model) {
    	if($index = $this->getRaw($model)) {
    		if($content = $this->arrayContent()) {
	    		if(isset($content[$index])) {
	    			return Yii::$app->formatter->asText($content[$index]);
	    		}
	    	}

	    	return Yii::$app->formatter->asText($index);
	    }

        return Yii::$app->formatter->asText(null);
    }
}
