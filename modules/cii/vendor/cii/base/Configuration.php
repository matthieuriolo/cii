<?php

namespace cii\base;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;

class Configuration extends Model {
	public $key;
	public $label;
	public $id;
	public $default = null;
	public $type = 'text';
	public $values = null;

	public function init() {
		if(!$this->key || !$this->id) {
			throw new InvalidConfigException();
		}

		if(!$this->label) {
			$this->label = ucfirst($this->key);
		}

		parent::init();
	}

	public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'Extension'),
            'label' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'default' => Yii::t('app', 'Default'),
            'value' => Yii::t('app', 'Value'),
        ];
    }

	public function getValue() {
		return Yii::$app->cii->setting($this->id, $this->key, null);
	}
}