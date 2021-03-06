<?php
namespace cii\fields;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

abstract class AbstractField extends Model {
	public $label;
	public $value;
	public $attribute;
	public $visible = true;

	public function rules() {
		return [
			[['value'], 'string', 'max' => 255]
		];
	}


	public function isVisible() {
		return true;
	}

	public function getRaw($model) {
		return $this->value;
    }

    public function getPreparedValue($model) {
        return $this->getRaw($model);
    }

    public function getView($model) {
        return Yii::$app->formatter->asText($this->getRaw($model));
    }

    abstract public function getEditable($model, $form);
}
