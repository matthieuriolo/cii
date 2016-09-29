<?php
namespace cii\fields\dropdown;

use Yii;
use cii\fields\DropdownField;
use cii\helpers\Html;

abstract class RelationField extends DropdownField {
	public $baseUri = 'cii/view';
	public $label_property = 'name';
	public $pk_property = 'id';

	public function getRaw($model) {
		return $this->value;
    }

	public function getViewUrl() {
		return Yii::$app->seo->relativeAdminRoute('modules/' . $this->baseUri);
	}

	abstract public function findModel($pk);

	public function getView($model) {
		$propLabel = $this->label_property;
		$propPK = $this->pk_property;

		if($raw = $this->getRaw($model)) {
			if(!is_object($raw)) {
				$raw = $this->findModel($raw);
			}
			
			$val = $raw->$propPK;
			if($val) {
				$name = Yii::$app->formatter->asText($raw->$propLabel);
		    	return Html::a($name, [$this->getViewUrl(), 'id' => $val]);
		    }
		}

	    return Yii::$app->formatter->asText(null);
    }
}
